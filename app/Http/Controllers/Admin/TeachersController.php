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
use App\Models\TeacherSlots;
use App\Models\Bookings;
use App\Models\Notifications;
use App\Models\AssignTimes;
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
            'phone_number' => 'required|numeric',
            'password' => 'required|min:6',
            'course_division' => 'required'
        ],[
            "phone_number.*"    => "The phone number is invalid",
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
            'phone_number' => 'required|numeric',
            'course_division' => 'required',
            'password' => 'nullable|min:6',
        ],[
            "phone_number.*"    => "The phone number is invalid",
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
        $date_search = $teacher_search =  $division_search = null;
        
        if ($request->has('assigned_date')) {
            $date_search = $request->assigned_date;
        }
        if ($request->has('teacher')) {
            $teacher_search = $request->teacher;
        }
        if ($request->has('course_division')) {
            $division_search = $request->course_division;
        }
       
        // DB::enableQueryLog();
        $query = AssignTeachers::with(['teacher','course_division','slots'])
                ->where('is_deleted',0)
                ->orderBy('id','DESC');

        if($date_search){
            $query->where('assigned_date', $date_search);
        }
        if($teacher_search){
            $query->where('teacher_id', $teacher_search);
        }
        if($division_search){
            $query->where('module_id', $division_search);
        }

        $assigned = $query->paginate(10);
        // dd(DB::getQueryLog());
        $teachers = User::with(['user_details','teacher_divisions'])
                        ->where('user_type', 'staff')
                        ->where('is_active',1)
                        ->where('is_deleted',0)
                        ->get();
        $teacherdivisions = $divisions = [];
        if($teacher_search != ''){
            $teacherdivisions = TeacherDivisions::with(['course_division'])->where('teacher_id', $teacher_search)->where('is_deleted',0)->orderBy('id', 'ASC')->get();
        }else{
            $divisions = CourseDivisions::where('is_active',1)->orderBy('id','ASC')->get();
        }

        return  view('admin.assign-teachers.index',compact('assigned','teachers','divisions','teacherdivisions','date_search','teacher_search','division_search'));
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
    
    public function getTeacherDivisions(Request $request){
        $teacherId = $request->id;
        $divisions = TeacherDivisions::with(['course_division'])->where('teacher_id', $teacherId)->where('is_deleted',0)->orderBy('id', 'ASC')->get();

        $options = '';
        foreach($divisions as $div){
            $options .= '<option value="'.$div->course_division->id.'">'.$div->course_division->title.'</option>';
        }
        return $options;
    }

    public function getTeacherDivisionsFilter(Request $request){
        $teacherId = $request->id;
        $divisions = $teacher_divisions = [];
        if($teacherId != ''){
            $divisions = TeacherDivisions::with(['course_division'])->where('teacher_id', $teacherId)->where('is_deleted',0)->orderBy('id', 'ASC')->get();
        }else{
            $teacher_divisions = CourseDivisions::where('is_active', 1)->orderBy('title','asc')->get();
        }
   
        $options = '';
        if(!empty($divisions)){
            foreach($divisions as $div){
                $options .= '<option value="'.$div->course_division->id.'">'.$div->course_division->title.'</option>';
            }
        }

        if(!empty($teacher_divisions)){
            foreach($teacher_divisions as $tdiv){
                $options .= '<option value="'.$tdiv->id.'">'.$tdiv->title.'</option>';
            }
        }
       
        return $options;
    }

    public function storeAssign(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_date' => 'required',
            'class_date_to' => 'required',
            'teacher' => 'required',
            'course_division' => 'required',
            'interval' => 'required',
        ],[
            'class_date.required'=>'This field is required.',
            'class_date_to.required'=>"This field is required.",
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // echo '<pre>';
        // print_r($request->all());

        $dates = getDatesBetween2Dates($request->class_date, $request->class_date_to);
        
        $slots = [];
        foreach($request->times as $time){
           $slots[] = getTimeSlotHrMIn($request->interval, $time['from_time'], $time['to_time']);
        }
        $allSlots = array_merge(...$slots);
       
        $assignTimes = [];
        $assignSlots = [];
        foreach($dates as $dt){
            $assign = new AssignTeachers;
            $assign->teacher_id = $request->teacher;
            $assign->module_id = $request->course_division;
            $assign->assigned_date = $dt;
            $assign->time_interval = $request->interval;
            $assign->save();
            $assignedId = $assign->id;

            foreach($request->times as $time){
                $assignTimes[] = [
                    'start_time' => $time['from_time'],
                    'end_time' => $time['to_time'],
                    'assign_id' => $assignedId,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }

            if(!empty($allSlots)){
                foreach($allSlots as $sl){
                    $assignSlots[] = array(
                        'assigned_id' => $assignedId, 
                        'slot' => $sl,
                        'created_at' => date('Y-m-d H:i:s')
                    );
                }
            }
        }
        if(!empty($assignTimes)){
            AssignTimes::insert($assignTimes);
        }  
        if(!empty($assignSlots)){
            TeacherSlots::insert($assignSlots);
        }  
       
        flash('Teacher has been assigned successfully')->success();
        return redirect()->route('assign-teachers');
    }

    public function deleteAssign(Request $request){
        AssignTeachers::where('id', $request->id)->delete();
    }

    public function editAssign(Request $request, $id)
    {
        $assign = AssignTeachers::with(['times'])->find($id);

        $times = [];
        $assignTimes = $assign->times;

        foreach ($assignTimes as $i) {
            $arr = [];
            $arr['assign_id'] = $i->id;
            $arr['from_time'] = $i->start_time;
            $arr['to_time'] = $i->end_time;
            $times[] = $arr;
        }

        $times = json_encode($times);
        
        $teachers = User::with(['user_details','teacher_divisions'])
                        ->where('user_type', 'staff')
                        ->where('is_active',1)
                        ->where('is_deleted',0)
                        ->get();

        $divisions = TeacherDivisions::with(['course_division'])->where('teacher_id', $assign->teacher_id)->where('is_deleted',0)->orderBy('id', 'ASC')->get();
        
        return view('admin.assign-teachers.edit', compact('assign','teachers','divisions','times'));
    }

    public function updateAssign(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'class_date' => 'required',
            'teacher' => 'required',
            'course_division' => 'required',
            'interval' => 'required',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $assign = AssignTeachers::find($id);
        $assign->teacher_id = $request->teacher;
        $assign->module_id = $request->course_division;
        $assign->assigned_date = $request->class_date;
        $assign->start_time = $request->from_time;
        $assign->end_time = $request->to_time;
        $assign->time_interval = $request->interval;
        $assign->save();
        $assignedId = $assign->id;


        $slots = [];
        foreach($request->times as $time){
           $slots[] = getTimeSlotHrMIn($request->interval, $time['from_time'], $time['to_time']);
        }
        $allSlots = array_merge(...$slots);
    
        if(!empty($allSlots)){
            TeacherSlots::where('assigned_id', $id)->where('is_booked',0)->delete();
            $datas = [];
            foreach($allSlots as $sl){
                $check = TeacherSlots::where('assigned_id', $assignedId)->where('slot',$sl)->count();
                if($check == 0){
                    $datas[] = array(
                        'assigned_id' => $assignedId, 
                        'slot' => $sl,
                        'created_at' => date('Y-m-d H:i:s')
                    );
                }
            }
            if(!empty($datas)){
                TeacherSlots::insert($datas);
            }  
        }

        AssignTimes::where('assign_id',$assignedId)->delete();
        $assignTimes = [];
        foreach($request->times as $time){
            $assignTimes[] = [
                'start_time' => $time['from_time'],
                'end_time' => $time['to_time'],
                'assign_id' => $assignedId,
                'created_at' => date('Y-m-d H:i:s')
            ];
        }

        if(!empty($assignTimes)){
            AssignTimes::insert($assignTimes);
        } 

        flash('Teacher has been assigned successfully')->success();
        return redirect()->route('assign-teachers');
    }

    public function editAssignSlots(Request $request, $id)
    {
        $slots = TeacherSlots::where('is_deleted', 0)->where('assigned_id', $id)->get();
        $assign = AssignTeachers::with(['teacher','course_division'])->find($id);

        return view('admin.assign-teachers.edit-slot', compact('assign','slots'));
    }

    public function updateAssignSlots(Request $request, $id){
        $slots = $request->slot;
        TeacherSlots::whereIn('id',$slots)->delete();
        
        flash('Slots deleted successfully')->success();
        return redirect()->back();
    }

    public function cancelBooking(Request $request){
        $id = $request->id;
        $msg = $request->msg;
        $cancel = AssignTeachers::find($id);
        

        $cancel->update(['is_active'=>0]);
        $date = $cancel->assigned_date;
        TeacherSlots::where('assigned_id','=', $id)->update(['is_deleted'=>1]);
        $slots = TeacherSlots::where('assigned_id', '=', $id)->pluck('id')->toArray();

        $allBookings = Bookings::whereIn('slot_id', $slots)->where('is_cancelled', 0)->get();
        if($allBookings){
            Bookings::whereIn('slot_id', $slots)->where('is_cancelled', 0)->update(['is_cancelled'=>1, 'cancelled_by' => Auth::user()->id]);
            if($msg != ''){
                $message = $msg;
            }else{
                $message = 'Your booking for '.date("d M, Y",strtotime($date)).' has been cancelled by Admin';
            }
            $nots = [];
            foreach($allBookings as $book){
                $nots[] = [
                    'user_id' => $book->student_id,
                    'content' => $message
                ];
            }
            Notifications::insert($nots);
        }
    }
   
}
