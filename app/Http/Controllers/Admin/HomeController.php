<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\Courses;
use App\Models\CourseDivisions;
use App\Models\CoursePackages;
use App\Models\PackageModules;
use App\Models\CourseClasses;
use App\Models\TeacherDivisions;
use App\Models\States;
use App\Models\PackageClasses;
use App\Models\CronAddClasses;
use App\Models\Permissions;
use App\Models\UserPermissions;
use Auth;
use Validator;
use Storage;
use Str;
use File;
use Hash;
use DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }

    public function index(){
        // $students = User::where('user_type','student')->withCount(['approved', 'rejected'])->get();
        // echo '<pre>';
        // print_r($students);
        // die;

        // $total_students = User::where('user_type','student')->count();
        // $approved_students = User::where('user_type','student')->where('is_approved', 1)->count();
        // $rejected_students = User::where('user_type','student')->where('is_approved', 2)->count();
        return  view('admin.dashboard');
    }

    public function dashboardCounts(Request $request)
    {
        $startDate = $request->start;
        $endDate = $request->end;

        $data['total_students'] = User::whereDate('created_at', '>=', $startDate)
                                ->whereDate('created_at', '<=', $endDate)
                                ->where('user_type','student')
                                ->where('is_deleted', 0)
                                ->count();

        $data['approved_students'] = User::whereDate('created_at', '>=', $startDate)
                                ->whereDate('created_at', '<=', $endDate)
                                ->where('user_type','student')
                                ->where('is_deleted', 0)
                                ->where('is_approved', 1)
                                ->count();

        $data['rejected_students'] = User::whereDate('created_at', '>=', $startDate)
                                ->whereDate('created_at', '<=', $endDate)
                                ->where('user_type','student')
                                ->where('is_deleted', 0)
                                ->where('is_approved', 2)
                                ->count();

        return json_encode(array('status' => true, 'data' => $data));
    }

    public function getAllCourses(){
        $query = Courses::select('*')
                    ->where('is_deleted',0)
                    ->orderBy('id','DESC');
        $courses = $query->paginate(10);
        return  view('admin.courses.index',compact('courses'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function createCourse()
    {
        return view('admin.courses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'banner_image' => 'required'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('banner_image')) {
            $uploadedFile = $request->file('banner_image');
            $filename =    strtolower(Str::random(2)).time().'.'. $uploadedFile->getClientOriginalName();
            $name = Storage::disk('public')->putFileAs(
                'courses',
                $uploadedFile,
                $filename
            );
           $imageUrl = Storage::url($name);
        }   

        $course = Courses::create([
            'name' => $request->name,
            'description' => $request->description,
            'banner_image' => $imageUrl
        ]);

        
        flash('Course has been created successfully')->success();
        return redirect()->route('all-courses');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editCourse(Request $request, $id)
    {
        $course = Courses::findOrFail($id);
        $course_divisions = CourseDivisions::where('courses_id', $id)->get();

        $divisions = [];

        foreach ($course_divisions as $div) {
            $arr = [];
            $arr['division_name'] = $div->title;
            $arr['division_description'] = $div->description;
            $arr['division_status'] = $div->is_active;
            $divisions[] = $arr;
        }

        $divisions = json_encode($divisions);
        return view('admin.courses.edit', compact('course','divisions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateCourse(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $course = Courses::findOrFail($id);

        $presentImage = $course->banner_image;
        $imageUrl = '';
        if ($request->hasFile('banner_image')) {
            $uploadedFile = $request->file('banner_image');
            $filename =    strtolower(Str::random(2)).time().'.'. $uploadedFile->getClientOriginalName();
            $name = Storage::disk('public')->putFileAs(
                'courses',
                $uploadedFile,
                $filename
            );
           $imageUrl = Storage::url($name);
           if($presentImage != '' && File::exists(public_path($presentImage))){
                unlink(public_path($presentImage));
            }
        }   

        $course->name = $request->name;
        $course->description = $request->description;
        $course->banner_image = ($imageUrl != '') ? $imageUrl : $presentImage;
        $course->is_active = $request->is_active;
        $course->save();

        flash('Course has been updated successfully')->success();
        return redirect()->route('all-courses');
    }

    public function deleteCourse(Request $request){
        Courses::where('id', $request->id)->update(['is_deleted' => 1]);
    }

    public function getAllDivisions(Request $request){
        $title_search = $course_search = $status_search = '';
        if ($request->has('title')) {
            $title_search = $request->title;
        }
        if ($request->has('course')) {
            $course_search = $request->course;
        }
        if ($request->has('is_active')) {
            $status_search = $request->is_active;
        }

        $query = CourseDivisions::with(['course_name'])->select('*')
                    ->where('is_deleted',0)
                    ->orderBy('id','DESC');

        if($title_search){
            $query->where('title', 'LIKE', "%$title_search%");
        }
        if($course_search){
            $query->where('courses_id', $course_search);
        }

        if($status_search != ''){
            $query->where('is_active', $status_search);
        }

        $divisions = $query->paginate(10);

        $courses = Courses::where('is_deleted',0)->where('is_active',1)->orderBy('name','ASC')->get();
        return  view('admin.course_divisions.index',compact('divisions','courses','title_search','course_search','status_search'));
    }

    public function createDivision()
    {
        $courses = Courses::where('is_active',1)->where('is_deleted',0)->orderBy('name','ASC')->get();
        return view('admin.course_divisions.create',compact('courses'));
    }

    public function storeDivision(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'division_name' => 'required',
            'description' => 'required',
            'course_id' => 'required'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $course = CourseDivisions::create([
            'courses_id' => $request->course_id,
            'title' =>  $request->division_name,
            'description' => $request->description ?? NULL
        ]);

        flash('Course Division has been created successfully')->success();
        return redirect()->route('all-divisions');
    }

    public function editDivision(Request $request, $id)
    {
        $division = CourseDivisions::findOrFail($id);
        $courses = Courses::where('is_active',1)->where('is_deleted',0)->orderBy('name','ASC')->get();
        return view('admin.course_divisions.edit', compact('division','courses'));
    }

    public function updateDivision(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'division_name' => 'required',
            'description' => 'required',
            'course_id' => 'required'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $division = CourseDivisions::findOrFail($id);
        $division->courses_id = $request->course_id;
        $division->title = $request->division_name;
        $division->description = $request->description;
        $division->is_active = $request->is_active;
        $division->save();

        flash('Course Division has been updated successfully')->success();
        return redirect()->route('all-divisions');
    }

    public function deleteDivision(Request $request){
        CourseDivisions::where('id', $request->id)->update(['is_deleted' => 1]);
    }

    public function getAllCoursePackages(Request $request){

        $title_search = $course_search = $status_search = '';
        if ($request->has('title')) {
            $title_search = $request->title;
        }
        if ($request->has('course')) {
            $course_search = $request->course;
        }
        if ($request->has('is_active')) {
            $status_search = $request->is_active;
        }

        $query = CoursePackages::with(['course_name','active_package_modules'])->select('*')
                ->where('is_deleted',0)
                ->orderBy('id','DESC');

        if($title_search){
            $query->where('package_title', 'LIKE', "%$title_search%");
        }
        if($course_search){
            $query->where('courses_id', $course_search);
        }

        if($status_search != ''){
            $query->where('is_active', $status_search);
        }

        $packages = $query->paginate(10);

        $courses = Courses::where('is_deleted',0)->where('is_active',1)->orderBy('name','ASC')->get();

        return  view('admin.course_packages.index',compact('packages','courses','title_search','course_search','status_search'));
    }
    
    public function createPackage(){
        $courses = Courses::where('is_deleted',0)->where('is_active',1)->orderBy('name','ASC')->get();
        return   view("admin.course_packages.create", compact('courses'));
    }

    public function getCourseDivisions(Request $request){
        $courseId = $request->id;
        $divisions = CourseDivisions::where('courses_id', $courseId)->where('is_active',1)->orderBy('id', 'ASC')->get();

        $options = '';
        foreach($divisions as $div){
            $options .= '<option value="'.$div->id.'">'.$div->title.'</option>';
        }
        return $options;
    }

    public function storePackage(Request $request)
    {
        // print_r($request->all());die;
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'course' => 'required',
            'duration' => 'required',
            'fee' => 'required',
            'course_division' => 'required'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $package = CoursePackages::create([
            'courses_id' => $request->course,
            'package_title' => $request->title,
            'description' => $request->description,
            'duration' => $request->duration,
            'fees' => $request->fee,
        ]);

        if ($request->course_division) {
            $modules = [];
            foreach ($request->course_division as $div) {
                $modules[]= array(
                    'package_id' => $package->id,
                    'module_id' => $div,
                    'created_at' => date('Y-m-d H:i:s')
                );
            }
            PackageModules::insert($modules);
        }
        flash('Course package has been created successfully')->success();
        return redirect()->route('course-packages');
    }

    public function deletePackage(Request $request){
        CoursePackages::where('id', $request->id)->update(['is_deleted' => 1]);
    }

    public function editPackage(Request $request, $id)
    {
        $courses = Courses::where('is_deleted',0)->where('is_active',1)->orderBy('name','ASC')->get();
        $package = CoursePackages::with(['active_package_modules'])->find($id);

        $divisions = CourseDivisions::where('courses_id', $package->courses_id)->where('is_active',1)->orderBy('id','ASC')->get();
       
        $modules = [];
        if(!empty($package->active_package_modules)){
            foreach($package->active_package_modules as $module){
                $modules[] = $module->module_id;
            }
        }
        
        return view('admin.course_packages.edit', compact('courses','package','modules','divisions'));
    }

    public function updatePackage(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'course' => 'required',
            'duration' => 'required',
            'fee' => 'required',
            'course_division' => 'required'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $package = CoursePackages::findOrFail($id);
        $package->courses_id = $request->course;
        $package->package_title = $request->title;
        $package->description = $request->description;
        $package->duration = $request->duration;
        $package->fees = $request->fee;
        $package->is_active = $request->is_active;
        $package->save();

        if ($request->course_division) {
            $modules = [];
            PackageModules::where('package_id', $id)->update(['is_deleted' => 1]);
            foreach ($request->course_division as $div) {
                $modules[]= array(
                    'package_id' => $package->id,
                    'module_id' => $div,
                    'created_at' => date('Y-m-d H:i:s')
                );
            }
            PackageModules::insert($modules);
        }
        flash('Course Package has been updated successfully')->success();
        return redirect()->route('course-packages');
    }
   
    public function getAllClasses(Request $request){
        $title_search = $course_search =  $division_search = $package_search = null;
        if ($request->has('title')) {
            $title_search = $request->title;
        }
        if ($request->has('course')) {
            $course_search = $request->course;
        }
        if ($request->has('course_division')) {
            $division_search = $request->course_division;
        }
        if ($request->has('package')) {
            $package_search = $request->package;
        }

        $courses = Courses::where('is_deleted',0)->where('is_active',1)->orderBy('name','ASC')->get();
        $query = CourseClasses::with(['course','course_division','packages'])->select('*')
                ->where('is_deleted',0)
                ->orderBy('order','ASC');

        if($title_search){
            $query->where('class_name', 'LIKE', "%$title_search%");
        }
        if($course_search){
            $query->where('course_id', $course_search);
        }
        if($division_search){
            $query->where('module_id', $division_search);
        }

        if($package_search){
            $query->whereHas('packages', function ($query)  use($package_search) {
                $query->where('package_id', $package_search);
            });
        }

        $classes = $query->paginate(10);

        if($course_search){
            $divisions = CourseDivisions::where('courses_id', $course_search)->where('is_active',1)->where('is_deleted',0)->orderBy('title','ASC')->get();
            $packages = CoursePackages::where('courses_id', $course_search)->where('is_active',1)->where('is_deleted',0)->orderBy('package_title','ASC')->get();
        }else{
            $divisions = CourseDivisions::where('is_active',1)->where('is_deleted',0)->orderBy('title','ASC')->get();
            $packages = CoursePackages::where('is_active',1)->where('is_deleted',0)->orderBy('package_title','ASC')->get();
        }

        return  view('admin.classes.index',compact('classes','packages','package_search','courses','divisions','title_search','course_search','division_search'));
    }

    public function createClass()
    {
        $courses = Courses::where('is_deleted',0)->where('is_active',1)->orderBy('name','ASC')->get();
        return   view("admin.classes.create", compact('courses'));
    }

    public function storeClass(Request $request)
    {
        // echo '<pre>'; print_r($request->all());die;
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'course' => 'required',
            'course_division' => 'required',
            'packages' => 'required'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $class = new CourseClasses();
        $class->module_id = $request->course_division;
        $class->course_id  = $request->course ;
        $class->class_name = $request->title;
        $class->order = $request->order;
        $class->is_mandatory = $request->mandatory;
        $class->save();

        $classId = $class->id;
        $package = [];
        if ($request->packages) {
            foreach ($request->packages as $pack) {
                $package[] = array(
                    'package_id' => $pack,
                    'class_id' => $classId,
                    'created_at' => date('Y-m-d H:i:s')
                );

            }
        }
        
        PackageClasses::insert($package);
        CronAddClasses::insert($package);

        flash('Class has been created successfully')->success();
        return redirect()->route('classes');
    }

    public function deleteClass(Request $request){
        CourseClasses::where('id', $request->id)->update(['is_deleted' => 1]);
    }

    public function editClass(Request $request, $id)
    {
        $courses = Courses::where('is_deleted',0)->where('is_active',1)->orderBy('name','ASC')->get();
        $classes = CourseClasses::with(['packages'])->find($id);

        $divisions = CourseDivisions::where('courses_id', $classes->course_id)->where('is_active',1)->where('is_deleted',0)->orderBy('id','ASC')->get();
        $packages = CoursePackages::where('courses_id', $classes->course_id)->where('is_active',1)->where('is_deleted',0)->orderBy('package_title','ASC')->get();

        $packs = [];
        if(!empty($classes->packages)){
            foreach($classes->packages as $pack){
                $packs[] = $pack->package_id;
            }
        }
        return view('admin.classes.edit', compact('courses','classes','packs','packages','divisions'));
    }

    public function updateClass(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'course' => 'required',
            'course_division' => 'required',
            'order' => 'required',
            'packages' => 'required'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $class = CourseClasses::with(['packages'])->find($id);

        $packs = [];
        if(!empty($class->packages)){
            foreach($class->packages as $pack){
                $packs[] = $pack->package_id;
            }
        }

        $newArray = $request->packages;
       
        $class->module_id = $request->course_division;
        $class->course_id = $request->course;
        $class->class_name = $request->title;
        $class->order = $request->order;
        $class->is_mandatory = $request->mandatory;
        $class->is_active = $request->is_active;
        $class->save();

        if(!empty($newArray)){
            if ($packs != $newArray) {
                $diffAdd = array_diff($newArray, $packs);
                $diffRemove = array_diff($packs, $newArray);
                
                if(!empty($diffAdd)){
                    $package = [];
                    foreach ($diffAdd as $addvalue){
                        $package[] = array(
                            'package_id' => $addvalue,
                            'class_id' => $class->id,
                            'created_at' => date('Y-m-d H:i:s')
                        );
                    }
                    PackageClasses::insert($package);
                    CronAddClasses::insert($package);
                }
                if(!empty($diffRemove)){
                    foreach ($diffRemove as $remvalue){
                        PackageClasses::where("package_id", $remvalue)->where('class_id',$class->id)->update(['is_deleted' => 1]);
                    }
                }
            }  
        }

        flash('Class has been updated successfully')->success();
        return redirect()->route('classes');
    }


    public function getCountryStates(Request $request){
        $query = States::select('*');
        if(isset($request->id)){
            $query->where('country_id', $request->id);
        }
        $states = $query->orderBy('name','ASC')->get();
        $options = '';
        foreach($states as $div){
            $options .= '<option value="'.$div->id.'">'.$div->name.'</option>';
        }
        return $options;
    }

    public function getCoursePackages(Request $request){
        $packages = CoursePackages::with(['course_name'])->select('*')
                    ->where('courses_id', $request->id)
                    ->where('is_active',1)
                    ->where('is_deleted',0)
                    ->orderBy('package_title','ASC')
                    ->get();
        $options = '';
        foreach($packages as $div){
            $options .= '<option value="'.$div->id.'" data-id="'.$div->duration.'">'.$div->package_title.'</option>';
        }
        return $options;
    }

    public function getCourseDivisionsPackages(Request $request){
        $courseId = $request->id;
        $divisions = CourseDivisions::where('courses_id', $courseId)->where('is_active',1)->orderBy('id', 'ASC')->get();

        $divisionOptions = '';
        foreach($divisions as $div){
            $divisionOptions .= '<option value="'.$div->id.'">'.$div->title.'</option>';
        }

        $packages = CoursePackages::where('courses_id', $courseId)
                                ->where('is_active',1)
                                ->where('is_deleted',0)
                                ->orderBy('package_title','ASC')
                                ->get();
        $packOptions = '';
        foreach($packages as $pack){
            $packOptions .= '<option value="'.$pack->id.'">'.$pack->package_title.'</option>';
        }

        return json_encode(array('divisions'=> $divisionOptions, 'packages' => $packOptions));
    }

    public function profile(){
        $user = User::with(['user_details'])->find(Auth::user()->id);
        return   view("admin.profile", compact('user'));
    }

    public function updateProfile(Request $request){
        $id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|string|email|max:100|unique:users,email,'.$id,
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::with(['user_details'])->findOrFail($id);

        $presentLogo = $user->user_details->profile_image;
        $logo = '';
        if ($request->hasFile('profile_image')) {
            $uploadedFilel = $request->file('profile_image');
            $filenamel =    strtolower(Str::random(2)).time().'.'. $uploadedFilel->getClientOriginalName();
            $namel = Storage::disk('public')->putFileAs(
                'users/'.$id,
                $uploadedFilel,
                $filenamel
            );
            $logo = Storage::url($namel);
            if($presentLogo != '' && File::exists(public_path($presentLogo))){
                unlink(public_path($presentLogo));
            }
        } 
        $user->name = $request->first_name.' '.$request->last_name;
        $user->email = $request->email;
        if($request->password != ''){
            $user->password = Hash::make($request->password);
        }
        $user->save();
        $userId = $user->id;
       
        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'profile_image' => ($logo != '') ? $logo : $presentLogo,
        ];
        UserDetails::where('user_id', $userId)->update($data);

        flash('Profile details updated successfully')->success();
        return redirect()->route('profile');
    }

    public function getAllAdmins(){
        $query = User::with(['user_details','user_permissions'])
                ->where('user_type', 'admin')
                ->where('is_deleted',0)
                ->orderBy('id','DESC');
        $admins = $query->paginate(10);
    
        return  view('admin.admin_users.index',compact('admins'));
    }

    public function createAdmin()
    {
        $permissions = Permissions::orderBy('title', 'ASC')->get();
        return   view("admin.admin_users.create", compact('permissions'));
    }

    public function storeAdmin(Request $request)
    {
        // echo '<pre>'; print_r($request->all());die;
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|min:6',
            'permissions' => 'required'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $user = new User;
        $user->user_type = 'admin';
        $user->name = $request->first_name.' '.$request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->is_approved = 1;
        $user->save();
        $userId = $user->id;
        if($userId){
            $profileImage = '';
            if ($request->hasFile('profile_image')) {
                $uploadedFile = $request->file('profile_image');
                $filename =    strtolower(Str::random(2)).time().'.'. $uploadedFile->getClientOriginalName();
                $name = Storage::disk('public')->putFileAs(
                    'users/'.$userId,
                    $uploadedFile,
                    $filename
                );
               $profileImage = Storage::url($name);
            } 

            $uDetails = new UserDetails();
            $uDetails->user_id = $user->id;
            $uDetails->first_name = $request->first_name;
            $uDetails->last_name = $request->last_name;
            $uDetails->phone_number = $request->phone_number;
            $uDetails->profile_image = $profileImage;
            $uDetails->save();
            $permissions = [];
            if(!empty($request->permissions)){
                foreach ($request->permissions as $per){
                    $permissions[] = [
                                    "user_id"      =>   $userId,
                                    "permission_id"=>     $per
                                    ];
                }
                UserPermissions::insert($permissions);
            }
        }
        flash('Admin created successfully')->success();
        return redirect()->route('admins');
    }

    public function editAdmin(Request $request, $id)
    {
        $user = User::with(['user_details','user_permissions'])->find($id);
        $permissions = Permissions::orderBy('title', 'ASC')->get();
        $user_permissions = $user->user_permissions->pluck('permission_id')->toArray();
        return   view("admin.admin_users.edit", compact('permissions','user','user_permissions'));
    }

    public function updateAdmin(Request $request, $id)
    {
        // echo '<pre>'; print_r($request->all());die;
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|string|email|max:100|unique:users,email,'.$id,
            'permissions' => 'required',
            'password' => 'nullable|min:6',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $user = User::with(['user_details','user_permissions'])->findOrFail($id);

        $presentImage = $user->user_details->profile_image;
        $imageUrl = '';
        if ($request->hasFile('profile_image')) {
            $uploadedFile = $request->file('profile_image');
            $filename =    strtolower(Str::random(2)).time().'.'. $uploadedFile->getClientOriginalName();
            $name = Storage::disk('public')->putFileAs(
                'users/'.$id,
                $uploadedFile,
                $filename
            );
           $imageUrl = Storage::url($name);
           if($presentImage != '' && File::exists(public_path($presentImage))){
                unlink(public_path($presentImage));
            }
        }  
        
        $user->name = $request->first_name.' '.$request->last_name;
        $user->email = $request->email;
        if($request->password != ''){
            $user->password = Hash::make($request->password);
        }
        $user->is_active = $request->is_active;
        $user->save();
        $userId = $user->id;

        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'profile_image' => ($imageUrl != '') ? $imageUrl : $presentImage,
        ];
        UserDetails::where('user_id', $userId)->update($data);

        if(!empty($request->permissions)){
            UserPermissions::where('user_id', $userId)->delete();
            foreach ($request->permissions as $per){
                $permissions[] = [
                                "user_id"      =>   $userId,
                                "permission_id"=>     $per
                                ];
            }
            UserPermissions::insert($permissions);
        }

        flash('Admin details updated successfully')->success();
        return redirect()->route('admins');
    }

    public function deleteAdmin(Request $request){
        User::where('id', $request->id)->update(['is_deleted' => 1]);
    }
}
