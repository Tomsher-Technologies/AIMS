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
use App\Models\AssignTeachers;
use Auth;
use Validator;
use Storage;
use Str;
use File;
use Hash;
use DB;

class TeachersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }

    public function getAllTeachers(Request $request){
        $query = User::with(['user_details','teacher_divisions'])
                ->where('user_type', 'staff')
                ->where('is_deleted',0)
                ->orderBy('id','DESC');
        $teachers = $query->paginate(10);
    
        return  view('admin.teachers.index',compact('teachers'));
    }

    public function createTeacher()
    {
        $divisions = CourseDivisions::with(['course_name'])
                                ->where('is_active',1)->orderBy('courses_id','ASC')->get();
        return   view("admin.teachers.create", compact('divisions'));
    }

    public function storeTeacher(Request $request)
    {
        // echo '<pre>'; print_r($request->all());die;
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|string|email|max:100|unique:users',
            'phone_number' => 'required',
            'password' => 'required',
            'course_division' => 'required'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = new User;
        $user->user_type = 'staff';
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
        }

        if(!empty($request->course_division)){
            foreach ($request->course_division as $value){
                TeacherDivisions::create([
                    "teacher_id"      =>   $userId,
                    "module_id"=>     $value
                    ]);
            }
        }
        flash('Teacher has been created successfully')->success();
        return redirect()->route('teachers');
    }

    public function deleteTeacher(Request $request){
        User::where('id', $request->id)->update(['is_deleted' => 1]);
    }

    public function editTeacher(Request $request, $id)
    {
        $teacher = User::with(['user_details','teacher_divisions'])->find($id);
        $divisions = CourseDivisions::with(['course_name'])->where('is_active',1)->orderBy('courses_id','ASC')->get();

        $modules = [];
        if(!empty($teacher->teacher_divisions)){
            foreach($teacher->teacher_divisions as $module){
                $modules[] = $module->module_id;
            }
        }
        return view('admin.teachers.edit', compact('teacher','divisions','modules'));
    }

    public function updateTeacher(Request $request, $id)
    {
        // echo '<pre>'; print_r($request->all());die;
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|string|email|max:100|unique:users,email,'.$id,
            'phone_number' => 'required',
            'course_division' => 'required'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $newArray = $request->course_division;
       
        $user = User::with(['user_details','teacher_divisions'])->findOrFail($id);

        $modules = [];
        if(!empty($user->teacher_divisions)){
            foreach($user->teacher_divisions as $module){
                $modules[] = $module->module_id;
            }
        }
  
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

        if(!empty($request->course_division)){

            if ($modules != $newArray) {
                $diffAdd = array_diff($newArray, $modules);
                $diffRemove = array_diff($modules, $newArray);
                
                if(!empty($diffAdd)){
                    foreach ($diffAdd as $addvalue){
                        TeacherDivisions::create([
                            "teacher_id"      =>   $userId,
                            "module_id"=>     $addvalue
                            ]);
                    }
                }
                if(!empty($diffRemove)){
                    foreach ($diffRemove as $remvalue){
                        TeacherDivisions::where("teacher_id", $userId)->where('module_id',$remvalue)->update(['is_deleted' => 1]);
                    }
                }
            }  
        }
        flash('Teacher has been updated successfully')->success();
        return redirect()->route('teachers');
    }

    public function getAllAssignedTeachers (Request $request){
        $query = AssignTeachers::with(['teachers','course_division'])
                ->where('is_deleted',0)
                ->orderBy('id','DESC');
        $assigned = $query->paginate(10);

        return  view('admin.assign-teachers.index',compact('assigned'));
    }

    public function createAssign()
    {
        $teachers = User::with(['user_details','teacher_divisions'])
                        ->where('user_type', 'staff')
                        ->where('is_active',1)
                        ->where('is_deleted',0)
                        ->get();
        
        return   view("admin.assign-teachers.create", compact('teachers'));
    }
}
