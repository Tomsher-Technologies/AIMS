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

        $total_students = User::where('user_type','student')->count();
        $approved_students = User::where('user_type','student')->where('is_approved', 1)->count();
        $rejected_students = User::where('user_type','student')->where('is_approved', 2)->count();
        return  view('admin.dashboard',compact('total_students','approved_students','rejected_students'));
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

        if ($request->divisions) {
            foreach ($request->divisions as $div) {
                if(isset($div['division_name']) && $div['division_name'] != ''){
                    CourseDivisions::create([
                        'courses_id' => $course->id,
                        'title' =>  $div['division_name'],
                        'description' =>  isset($div['division_description']) ? $div['division_description'] : NULL,
                        'is_active' => isset($div['division_status']) ? $div['division_status'] : 1,
                    ]);
                }
            }
        }
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

        if($request->divisions) {
            CourseDivisions::where('courses_id', $id)->delete();
            foreach ($request->divisions as $div) {
                if(isset($div['division_name']) && $div['division_name'] != ''){
                    CourseDivisions::create([
                        'courses_id' => $course->id,
                        'title' =>  $div['division_name'],
                        'description' =>  isset($div['division_description']) ? $div['division_description'] : NULL,
                        'is_active' => isset($div['division_status']) ? $div['division_status'] : 1,
                    ]);
                }
            }
        }
        flash('Course has been updated successfully')->success();
        return redirect()->route('all-courses');
    }

    public function deleteCourse(Request $request){
        Courses::where('id', $request->id)->update(['is_deleted' => 1]);
    }

    public function getAllCoursePackages(){
        $query = CoursePackages::with(['course_name','active_package_modules'])->select('*')
                ->where('is_deleted',0)
                ->orderBy('id','DESC');
        $packages = $query->paginate(10);
        return  view('admin.course_packages.index',compact('packages'));
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
        $title_search = $course_search =  $division_search = null;
        if ($request->has('title')) {
            $title_search = $request->title;
        }
        if ($request->has('course')) {
            $course_search = $request->course;
        }
        if ($request->has('course_division')) {
            $division_search = $request->course_division;
        }

        $courses = Courses::where('is_deleted',0)->where('is_active',1)->orderBy('name','ASC')->get();
        $query = CourseClasses::with(['course','course_division'])->select('*')
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
        $classes = $query->paginate(10);

        if($course_search){
            $divisions = CourseDivisions::where('courses_id', $course_search)->where('is_active',1)->orderBy('id','ASC')->get();
        }else{
            $divisions = CourseDivisions::where('is_active',1)->orderBy('id','ASC')->get();
        }
        return  view('admin.classes.index',compact('classes','courses','divisions','title_search','course_search','division_search'));
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
            'order' => 'required'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $package = CourseClasses::create([
            'module_id' => $request->course_division,
            'course_id' => $request->course,
            'class_name' => $request->title,
            'order' => $request->order,
            'is_mandatory' => $request->mandatory,
        ]);

        flash('Class has been created successfully')->success();
        return redirect()->route('classes');
    }

    public function deleteClass(Request $request){
        CourseClasses::where('id', $request->id)->update(['is_deleted' => 1]);
    }

    public function editClass(Request $request, $id)
    {
        $courses = Courses::where('is_deleted',0)->where('is_active',1)->orderBy('name','ASC')->get();
        $classes = CourseClasses::find($id);

        $divisions = CourseDivisions::where('courses_id', $classes->course_id)->where('is_active',1)->orderBy('id','ASC')->get();
       
        return view('admin.classes.edit', compact('courses','classes','divisions'));
    }

    public function updateClass(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'course' => 'required',
            'course_division' => 'required',
            'order' => 'required'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $class = CourseClasses::findOrFail($id);
        $class->module_id = $request->course_division;
        $class->course_id = $request->course;
        $class->class_name = $request->title;
        $class->order = $request->order;
        $class->is_mandatory = $request->mandatory;
        $class->is_active = $request->is_active;
        $class->save();

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

   
   
}
