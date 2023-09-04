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
use App\Models\Countries;
use App\Models\StudentClasses;
use App\Models\StudentPackages;
use App\Models\States;
use App\Models\Bookings;
use App\Models\Notifications;
use App\Models\Remarks;
use App\Models\PackageClasses;
use App\Models\Attendances;
use App\Models\AttendanceStudents;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Auth;
use Validator;
use Storage;
use Str;
use File;
use Hash;
use DB;
use Session;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }

    public function getAllStudents(Request $request){
        $title_search = $course_search =  $package_search = $status_search = null;
        
        if ($request->has('title')) {
            $title_search = $request->title;
        }
        if ($request->has('course')) {
            $course_search = $request->course;
        }
        if ($request->has('package')) {
            $package_search = $request->package;
        }
        if ($request->has('status')) {
            $status_search = $request->status;
        }

        $query = User::with(['user_details','student_packages'])
                ->where('user_type', 'student')
                ->where('is_deleted',0)
                ->orderBy('id','DESC');
        if($status_search){
            if($status_search == "active"){
                $query->where('is_active',1);
            }elseif($status_search == "inactive"){
                $query->where('is_active',0);
            }elseif($status_search == "approved"){
                $query->where('is_approved',1);
            }elseif($status_search == "rejected"){
                $query->where('is_approved',2);
            }elseif($status_search == "pending"){
                $query->where('is_approved',0);
            }elseif($status_search == "booking"){
                $query->where('booking_approval',0);
            }
        }

        if($title_search){
            $query->Where(function ($query) use ($title_search) {
                $query->orWhere('users.name', 'LIKE', "%$title_search%")
                ->orWhere('users.email', 'LIKE', "%$title_search%")
                ->orWhere('users.unique_id', 'LIKE', "%$title_search%");   
            }); 
        }
        if($package_search){
            $query->whereHas('student_packages', function ($query)  use($package_search) {
                $query->where('package_id', $package_search);
            });
        }
        if($course_search){
            $query->whereHas('student_packages', function ($query)  use($course_search) {
                $query->where('course_id', $course_search);
            });
        }
        

        $students = $query->paginate(10);
        $courses = Courses::where('is_deleted',0)->orderBy('name','ASC')->get();
        $package = CoursePackages::leftJoin('courses as co','course_packages.courses_id','=','co.id')
                        ->select('course_packages.id', 'course_packages.package_title')
                        ->where('co.is_deleted', 0)
                        ->where('course_packages.is_deleted',0)
                        ->orderBy('package_title','ASC')
                        ->get();
        return  view('admin.students.index',compact('students','package','courses','status_search','title_search','course_search','package_search'));
    }

    public function createStudent()
    {
        $courses = Courses::where('is_deleted',0)->where('is_active',1)->orderBy('name','ASC')->get();
        $countries = Countries::select('id','name')->orderBy('name','ASC')->get();
        return   view("admin.students.create", compact('courses','countries'));
    }

    public function storeStudent(Request $request)
    {
        // echo '<pre>'; print_r($request->all());die;
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|string|email|max:100|unique:users',
            'phone_number' => 'required|numeric',
            'password' => 'required|min:6',
            'course' => 'required',
            'gender' => 'required',
            'profile_image' => 'required',
            'enrollment_form' => 'required',
            'passport' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'course_package' => 'required'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $approvedCount = User::where('user_type', 'student')->where('is_approved',1)->count();
        $studentCode = 'ST'.($approvedCount+1);

        $user = new User;
        $user->user_type = 'student';
        $user->unique_id = $studentCode;
        $user->name = $request->first_name.' '.$request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->is_approved = 1;
        $user->is_active = 1;
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
            
            $enrollmentForm = '';
            if ($request->hasFile('enrollment_form')) {
                $uploadedFileForm = $request->file('enrollment_form');
                $filenameForm =    strtolower(Str::random(2)).time().'.'. $uploadedFileForm->getClientOriginalName();
                $nameForm = Storage::disk('public')->putFileAs(
                    'users/'.$userId,
                    $uploadedFileForm,
                    $filenameForm
                );
               $enrollmentForm = Storage::url($nameForm);
            } 

            $passportFront = '';
            if ($request->hasFile('passport')) {
                $uploadedFileFront = $request->file('passport');
                $passFrontfilename =    strtolower(Str::random(2)).time().'.'. $uploadedFileFront->getClientOriginalName();
                $frontname = Storage::disk('public')->putFileAs(
                    'users/'.$userId,
                    $uploadedFileFront,
                    $passFrontfilename
                );
               $passportFront = Storage::url($frontname);
            } 

            $uDetails = new UserDetails();
            $uDetails->user_id = $user->id;
            $uDetails->first_name = $request->first_name;
            $uDetails->last_name = $request->last_name;
            $uDetails->gender = $request->gender;
            $uDetails->date_of_birth = $request->dob;
            $uDetails->phone_number = $request->phone_number;
            $uDetails->address = $request->address;
            $uDetails->country = $request->country;
            $uDetails->state = $request->state;
            $uDetails->city = $request->city;
            $uDetails->passport = $passportFront;
            $uDetails->profile_image = $profileImage;
            $uDetails->enrollment_form = $enrollmentForm;
            $uDetails->save();

            $pack = new StudentPackages();
            $pack->user_id = $user->id;
            $pack->course_id = $request->course;
            $pack->package_id = $request->course_package;
            $pack->start_date = $request->start_date;
            $pack->end_date = $request->end_date;
            $pack->fee_pending = $request->fee_pending;
            $pack->due_date = ($request->fee_pending != 0) ? $request->due_date : '';
            $pack->save();
            $student_package_id = $pack->id;
            
            $classes = PackageClasses::where('package_id', $request->course_package)
                                    ->where('is_active',1)->where('is_deleted',0)->pluck('class_id')->toArray();
            $stud_classes = [];
            foreach($classes as $class){
                $stud_classes[] = array(
                    'user_id' => $user->id, 
                    'student_package_id' => $student_package_id, 
                    'course_package_id' => $request->course_package, 
                    'class_id' => $class, 
                    'start_date' => $request->start_date, 
                    'end_date' => $request->end_date, 
                    'created_at' => date('Y-m-d H:i:s')
                );
            }
            if(!empty($stud_classes)){
                StudentClasses::insert($stud_classes);
            }
        }

        flash('Student has been created successfully')->success();
        return redirect()->route('students');
    }

    public function deleteStudent(Request $request){
        User::where('id', $request->id)->update(['is_deleted' => 1]);
    }

    public function editStudent(Request $request, $id)
    {
        $student = User::with(['user_details','student_packages'])->find($id);
        $courses = Courses::where('is_deleted',0)->where('is_active',1)->orderBy('name','ASC')->get();
        if(isset($student->student_packages[0])){
            $packages = CoursePackages::where('courses_id',$student->student_packages[0]->course_id)
                                        ->where('is_active',1)
                                        ->where('is_deleted',0)
                                        ->orderBy('package_title','ASC')->get();
        }else{
            $packages = [];
        }
        
        $countries = Countries::select('id','name')->orderBy('name','ASC')->get();
        $states = States::select('*')->where('country_id', $student->user_details->country)->orderBy('name','ASC')->get();
        return view('admin.students.edit', compact('student','courses','packages','countries','states'));
    }

    public function updateStudent(Request $request, $id)
    {
        // echo '<pre>'; print_r($request->all());die;
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|string|email|max:100|unique:users,email,'.$id,
            'phone_number' => 'required|numeric',
            'course' => 'required',
            'gender' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'course_package' => 'required',
            'password' => 'nullable|min:6',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $user = User::with(['user_details','student_packages'])->findOrFail($id);

        $currentStudentPack = (isset($user->student_packages[0])) ? $user->student_packages[0]->id : '';
        $currentPackageId =  (isset($user->student_packages[0])) ? $user->student_packages[0]->package_id : '';
        $currentCourse =  (isset($user->student_packages[0])) ? $user->student_packages[0]->course_id : '';

        $oldStartDate = (isset($user->student_packages[0])) ? $user->student_packages[0]->start_date : '';
        $oldEndDate = (isset($user->student_packages[0])) ? $user->student_packages[0]->end_date : '';
        
        $user->name = $request->first_name.' '.$request->last_name;
        $user->email = $request->email;
        if($request->password != ''){
            $user->password = Hash::make($request->password);
        }
        $user->is_active = $request->is_active;
        $user->save();
        $userId = $user->id;
  
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
        $presentFormImage = $user->user_details->enrollment_form;
        $enrollmentForm = '';
        if ($request->hasFile('enrollment_form')) {
            $uploadedFileForm = $request->file('enrollment_form');
            $filenameForm =    strtolower(Str::random(2)).time().'.'. $uploadedFileForm->getClientOriginalName();
            $nameForm = Storage::disk('public')->putFileAs(
                'users/'.$id,
                $uploadedFileForm,
                $filenameForm
            );
            $enrollmentForm = Storage::url($nameForm);
            if($presentFormImage != '' && File::exists(public_path($presentFormImage))){
                unlink(public_path($presentFormImage));
            }
        } 
        $presentPassportImage = $user->user_details->passport;
        $passportFront = '';
        if ($request->hasFile('passport')) {
            $uploadedFileFront = $request->file('passport');
            $passFrontfilename =    strtolower(Str::random(2)).time().'.'. $uploadedFileFront->getClientOriginalName();
            $frontname = Storage::disk('public')->putFileAs(
                'users/'.$userId,
                $uploadedFileFront,
                $passFrontfilename
            );
            $passportFront = Storage::url($frontname);
            if($presentPassportImage != '' && File::exists(public_path($presentPassportImage))){
                unlink(public_path($presentPassportImage));
            }
        } 
    
        
        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'profile_image' => ($imageUrl != '') ? $imageUrl : $presentImage,
            'enrollment_form' => ($enrollmentForm != '') ? $enrollmentForm : $presentFormImage,
            'passport' => ($passportFront != '') ? $passportFront : $presentPassportImage,
            'gender' => $request->gender,
            'date_of_birth' => $request->dob,
            'address' => $request->address,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
        ];
        UserDetails::where('user_id', $userId)->update($data);

        if($currentPackageId != $request->course_package){
            if($currentStudentPack != ''){
                StudentPackages::where('id',$currentStudentPack)->update(['is_active' => 0]);
            }
            $pack = new StudentPackages();
            $pack->user_id = $user->id;
            $pack->course_id = $request->course;
            $pack->package_id = $request->course_package;
            $pack->start_date = $request->start_date;
            $pack->end_date = $request->end_date;
            $pack->fee_pending = $request->fee_pending;
            $pack->due_date = ($request->fee_pending != 0) ? $request->due_date : '';
            $pack->save();
            $student_package_id = $pack->id;

           
            if($currentStudentPack != ''){
                StudentClasses::where('student_package_id',$currentStudentPack)->update(['is_active' => 0]);
            }

            $classes = PackageClasses::where('package_id', $request->course_package)
                                    ->where('is_active',1)->where('is_deleted',0)->pluck('class_id')->toArray();
            $stud_classes = [];
            foreach($classes as $class){
                $stud_classes[] = array(
                    'user_id' => $user->id, 
                    'student_package_id' => $student_package_id, 
                    'course_package_id' => $request->course_package, 
                    'class_id' => $class, 
                    'start_date' => $request->start_date, 
                    'end_date' => $request->end_date, 
                    'created_at' => date('Y-m-d H:i:s')
                );
            }
            if(!empty($stud_classes)){
                StudentClasses::insert($stud_classes);
            }
        }else{
            $pack = StudentPackages::find($currentStudentPack);
            $pack->start_date = $request->start_date;
            $pack->end_date = $request->end_date;
            $pack->fee_pending = $request->fee_pending;
            $pack->due_date = ($request->fee_pending != 0) ? $request->due_date : '';
            $pack->save();
            $student_package_id = $pack->id;

            if($oldStartDate != $request->start_date || $oldEndDate != $request->end_date){
                // Update student classes start and end dates.
                $classes = StudentClasses::where('student_package_id','=',$currentStudentPack)->update(['start_date' => $request->start_date, 'end_date' => $request->end_date]);
            }
        }
       
        flash('Student has been updated successfully')->success();
        return redirect()->route('students');
    }

    public function approveStudent(Request $request){
        $user = User::find($request->id);
        $status = $request->status;
        $studentCode = '';
        if($status == 1){
            $approvedCount = User::where('user_type', 'student')->where('is_approved',1)->count();
            $studentCode = 'ST'.($approvedCount+1);
            $response = 'Approved';
        }else{
            $response = 'Rejected';
        }
        $user->update(['unique_id' => $studentCode,'is_approved' => $status]);
        return $response;
    }
   
    public function getAllStudentBookings(Request $request){
        if(!empty($request->all())){
            Session::put('booking_filter', $request->all());
        }else{
            Session::forget('booking_filter');
        }

        $title_search = $teacher_search =  $date_search = null;
        
        if ($request->has('title')) {
            $title_search = $request->title;
        }
        if ($request->has('teacher')) {
            $teacher_search = $request->teacher;
        }
        if ($request->has('date_search')) {
            $date_search = $request->date_search;
        }

        $query = Bookings::with(['student','student_details','teacher','course_division','slot','cancelledBy','createdBy'])
                ->where('is_deleted',0)
                ->orderBy('id','DESC');
        if(Auth::user()->user_type == 'staff'){
            $query->where('teacher_id', Auth::user()->id);
        }

        if($title_search){
            $query->Where(function ($query) use ($title_search) {
                $query->whereHas('student', function ($query)  use($title_search) {
                    $query->where('users.name', 'LIKE', "%$title_search%")
                        ->orWhere('users.email', 'LIKE', "%$title_search%")
                        ->orWhere('users.unique_id', 'LIKE', "%$title_search%"); 
                });
                $query->orWhereHas('student_details', function ($query)  use($title_search) {
                    $query->where('user_details.phone_number', 'LIKE', "%$title_search%"); 
                });
            }); 
        }
        if($date_search){
            $query->where('booking_date', $date_search);
        }
        if($teacher_search){
            $query->where('teacher_id', $teacher_search);
        }
        

        $bookings = $query->paginate(10);
        
        $teacher = User::where('user_type', 'staff')->where('is_deleted',0)->orderBy('name','ASC')->get();

        $package = CoursePackages::leftJoin('courses as co','course_packages.courses_id','=','co.id')
                        ->select('course_packages.id', 'course_packages.package_title')
                        ->where('co.is_deleted', 0)
                        ->where('course_packages.is_deleted',0)
                        ->orderBy('package_title','ASC')
                        ->get();
        return  view('admin.bookings.index',compact('bookings','package','teacher','title_search','teacher_search','date_search'));
    }

    public function cancelBooking(Request $request){
        $id = $request->id;
        $msg = $request->msg;
        $cancel = Bookings::findorfail($id);

        $slot_id = $cancel->slot_id;
        $student_id = $cancel->student_id;
        $date = $cancel->booking_date;

        $cancel->update(['is_cancelled'=>1, 'cancelled_by' => Auth::user()->id]);
        TeacherSlots::where('id', '=', $slot_id)->update(['is_booked'=>0]);
        if($cancel->is_cancelled == 1){
            if($msg != ''){
                $message = $msg;
            }else{
                $message = 'Your booking for '.date("d M, Y",strtotime($date)).' has been cancelled by Admin';
            }
            $not = new Notifications ();
            $not->user_id = $student_id;
            $not->content = $message;
            $not->save();
        }
    }

    public function remarks(Request $request){
        $title_search = null;

        if($request->has('title')){
            $title_search = $request->title;
        }

        $query = Remarks::leftJoin('users as ud','ud.id','=','remarks.student_id')
                    ->select('remarks.id','remarks.remarks','remarks.created_at','ud.name','ud.unique_id')
                    ->orderBy('remarks.id', 'DESC');
         if($title_search){
            $query->Where(function ($query) use ($title_search) {
                $query->where('ud.name', 'LIKE', "%$title_search%")
                        ->orWhere('ud.email', 'LIKE', "%$title_search%")
                        ->orWhere('ud.unique_id', 'LIKE', "%$title_search%")
                        ->orWhere('remarks.created_at', 'LIKE', "%$title_search%"); 
            }); 
         }           
        $remarks = $query->paginate(10);
        return  view('admin.students.remarks',compact('remarks','title_search'));
    }

    public function createBooking(Request $request){
        // DB::enableQueryLog();
        $students = StudentPackages::leftJoin('users as us','us.id','=','student_packages.user_id')
                            ->where('us.is_deleted',0)
                            ->where('us.is_approved',1)
                            ->where('us.is_active', 1)
                            ->where('student_packages.is_deleted',0)
                            ->where('student_packages.is_active', 1)
                            ->where('student_packages.end_date', '>', date('Y-m-d'))
                            ->orderBy('us.name', 'ASC')
                            ->select('us.name','us.id','us.unique_id')
                            ->get()->toArray();
                            // dd(DB::getQueryLog());
       
        return   view("admin.bookings.create", compact('students'));
    }

    public function getStudentDivisions(Request $request){
        $studentId = $request->id;
        $options = getStudentActiveCourseDivisions($studentId);
        return $options;
    }

    public function getAvailableTeachers(Request $request){
        $date = $request->date;
        $module_id = $request->module_id;
        // DB::enableQueryLog();
        $teachers = AssignTeachers::leftJoin('users as us','us.id','=','assign_teachers.teacher_id')
                                    ->where('assign_teachers.is_active',1)
                                    ->where('assign_teachers.is_deleted',0)
                                    ->where('assign_teachers.assigned_date', $date)
                                    ->where('assign_teachers.module_id', $module_id)
                                    ->where('us.is_active',1)
                                    ->where('us.is_deleted',0)->select('us.id','us.name','assign_teachers.id as assign_id')
                                    ->orderBy('us.name','ASC')->get()->toArray();
        // dd(DB::getQueryLog());
        $data['teachers'] = $teachers;
        $options = '<option value=""> Select </option>';  
        if($teachers){
            foreach($teachers as $slot){
                $options .= '<option value="'.$slot['id'].'">'.$slot['name'].'</option>';
            }
        }
        return $options; 
    }

    public function getTimeSlots(Request $request){
        $date = $request->date;
        $module_id = $request->module_id;
        $teacher_id = $request->teacher_id;

        $slots = TeacherSlots::leftjoin('assign_teachers as at','at.id','=','teacher_slots.assigned_id')
                                ->where('at.is_active',1)
                                ->where('at.is_deleted',0)
                                ->where('at.assigned_date', $date)
                                ->where('at.teacher_id', $teacher_id)
                                ->where('at.module_id', $module_id)
                                ->where('teacher_slots.is_booked',0)
                                ->where('teacher_slots.is_deleted',0)
                                ->select('teacher_slots.id','teacher_slots.slot')
                                ->orderBy('teacher_slots.id','ASC')->get()->toArray();

        $options = '<option value=""> Select </option>';  
        if($slots){
            foreach($slots as $st){
                $options .= '<option value="'.$st['id'].'">'.$st['slot'].'</option>';
            }
        }
        return $options; 
    }

    public function storeBooking(Request $request){
        $validator = Validator::make($request->all(), [
            'student' => 'required',
            'course_division' => 'required',
            'book_date' => 'required',
            'teacher' => 'required',
            'slot' => 'required'
        ],[
            "student.*"    =>"Student field is required",
            "course_division.*"=>"Course division field is required ",
            "book_date.*"=> "Booking date field is required.",
            "teacher.*"     =>'Available teacher field is required.',
            "slot.*"        =>'Time slot field is required.'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $book = new Bookings();
        $book->student_id = $request->student;
        $book->teacher_id = $request->teacher;
        $book->module_id = $request->course_division;
        $book->slot_id = $request->slot;
        $book->booking_date = $request->book_date;
        $book->created_by = Auth::user()->id;
        $book->save();

        if($book->id){
            TeacherSlots::where('id',$request->slot)->update(['is_booked' => 1]);
        }

        flash('Booking has been created successfully')->success();
        return redirect()->route('student.bookings');
    }

    public function getAttendance(){
        $courses = Courses::where('is_deleted',0)
                            ->where('is_active',1)
                            ->orderBy('name','ASC')
                            ->get();
        return  view('admin.attendance.index',compact('courses'));
    }

    public function getAttendanceList(Request $request){

        $title_search = $teacher_search =  $date_search = null;
        
        if ($request->has('title')) {
            $title_search = $request->title;
        }
        if ($request->has('teacher')) {
            $teacher_search = $request->teacher;
        }
        if ($request->has('date_search')) {
            $date_search = $request->date_search;
        }

        $query = Attendances::with(['course','course_division','class'])->orderBy('id','DESC');
        if($date_search){
            $query->where('attend_date', $date_search);
        }
        $attendances = $query->paginate(15);
        return  view('admin.attendance.list',compact('attendances','date_search'));
    }
    public function getStudentsEditList(Request $request, $id){
        $attend = Attendances::with(['class'])->find($id);
        $class_id = $attend->class_id;
        $date = $attend->attend_date;
        $course_division = $attend->division_id;
        $course = $attend->course_id;
        $class_name = $attend->class->class_name;
        //Get Students List for the selected class and date
        // DB::enableQueryLog();
        $students = StudentClasses::leftJoin('users as u','u.id','=','student_classes.user_id')
                                ->where('student_classes.class_id', $class_id)
                                ->where('student_classes.is_active', 1)
                                ->where('student_classes.is_attended', 1)
                                ->where('student_classes.attended_date', $date)
                                ->whereRaw('? between start_date and end_date', [$date])
                                ->select('u.name','u.email','u.id as user_id','student_classes.id','student_classes.is_attended','student_classes.attended_date','u.unique_id')
                                ->get();
        // dd(DB::getQueryLog());
       
        return  view('admin.attendance.edit_list',compact('students','class_id','id','date','course_division','course','class_name'));
    }

    public function getCourseClasses(Request $request){
        $moduleId = $request->id;
        $classes = CourseClasses::where('module_id', $moduleId)
                                ->where('is_deleted',0)
                                ->where('is_active',1)
                                ->orderBy('id', 'ASC')
                                ->get();

        $options = '';
        foreach($classes as $cla){
            $options .= '<option value="'.$cla->id.'">'.$cla->class_name.'</option>';
        }
        return $options;
    }

    public function getStudentsList(Request $request){
        $class_id = $request->class_id;
        $date = $request->date;
        $course_division = $request->course_division;
        $course = $request->course;
        //Get Students List for the selected class and date
        // DB::enableQueryLog();
        $students = StudentClasses::leftJoin('users as u','u.id','=','student_classes.user_id')
                                ->where('student_classes.class_id', $class_id)
                                ->where('student_classes.is_active', 1)
                                ->whereRaw('? between start_date and end_date', [$date])
                                ->select('u.name','u.email','u.id as user_id','student_classes.id','student_classes.is_attended','student_classes.attended_date','u.unique_id')
                                ->where('student_classes.is_attended', 0)
                                ->get();
        // dd(DB::getQueryLog());
        $viewdata = view('admin.attendance.students_list', compact('students','class_id','date','course_division','course'))->render();
        return $viewdata;
    }

    public function saveAttendance(Request $request){
        // echo '<pre>';
        // print_r($request->all());
        $class_id = $request->class_id;
        $date_value = $request->date_value;
        $course_id = $request->course_id;
        $division_id = $request->division_id;
        $attendance = $request->attendance;
        
        $check = Attendances::where('attend_date', $date_value)->where('class_id', $class_id)->first();
        
        if($check){
            $attendId = $check['id'];
            foreach ($attendance as $key => $value) {
                $saveData = [
                        'attend_id' => $attendId,
                        'student_id' => $key,
                        'status' => $value
                    ];
                AttendanceStudents::updateOrCreate(['attend_id' => $attendId, 'student_id' => $key],$saveData);
                if($value == 0){
                    $dateValue = null;
                }else{
                    $dateValue = $date_value;
                }
                StudentClasses::where('class_id', $class_id)->where('user_id', $key)->where('is_active',1)->update(['is_attended' => $value, 'attended_date' => $dateValue]);
            }
        }else{
            $attend = new Attendances();
            $attend->attend_date = $date_value;
            $attend->class_id = $class_id;
            $attend->course_id = $course_id;
            $attend->division_id = $division_id;
            $attend->save();
            $attendId = $attend->id;

            if(!empty($attendance)){
                $data = [];
                foreach($attendance as $key => $value){
                    $data[] = array(
                        'attend_id' => $attendId,
                        'student_id' => $key,
                        'status' => $value
                    );
                    if($value == 1){
                        StudentClasses::where('class_id', $class_id)->where('user_id', $key)->where('is_active',1)->update(['is_attended' => 1, 'attended_date' => $date_value]);
                    }
                }
                AttendanceStudents::insert($data);
            }
        }
        return 'success';
    }

    public function updateAttendance(Request $request){
        $id = $request->id;
        $user_id = $request->user_id;
        $status = $request->status;
        $date = $request->date;
        $class_id = $request->classid;
        // AttendanceStudents::updateOrCreate(['attend_id' => $id, 'student_id' => $user_id],['status' => $status]);
        AttendanceStudents::where('attend_id' , $id)->where('student_id',$user_id)->where('status',1)->delete();
        if($status == 0){
            $dateValue = null;
        }else{
            $dateValue = $date;
        }
        StudentClasses::where('class_id', $class_id)->where('user_id', $user_id)->update(['is_attended' => $status, 'attended_date' => $dateValue]);
    }
    
    public function getStudentsAttendanceList($id){
        $attend = Attendances::with(['class'])->find($id);
        $date = $attend->attend_date;
        $class_name = $attend->class->class_name;

        $students = AttendanceStudents::with(['student'])
                ->where('attend_id', $id)
                ->orderBy('id','ASC')->paginate(50);
        return  view('admin.attendance.view_list',compact('students','date','class_name'));
    }

    public function attendBooking(Request $request){
        $id = $request->id;
        if($request->status == 2){
            $book_success = 0;
        }else{
            $book_success = 1;
        }
        // Bookings::where('id', $id)->update(['is_attended'=> $request->status, 'attend_date' => date('Y-m-d'),'booking_success' => $book_success]);

        $book = Bookings::find($id);
        $student_id = $book->student_id;
        $book->is_attended = $request->status;
        $book->attend_date = date('Y-m-d');
        $book->booking_success = $book_success;
        $book->save();

        $count = Bookings::where('student_id', $student_id)->where('booking_success',0)->count();
        if($count >= 3){
            User::where('id', $student_id)->update(['booking_approval' => 0]);
        }
    }

    public function createBulkStudent(){
        return view('admin.students.bulk_create');
    }

    public function storeBulkStudent(Request $request){
        $validator = Validator::make($request->all(), [
            'student_file' => 'required|mimes:xlsx, csv, xls'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $students = User::where('user_type','student')
                        ->where('is_deleted',0)
                        ->orderBy('name','ASC')
                        ->get()->pluck('email')->toArray();
       
        $the_file = $request->file('student_file');
        
        $notFound = [];
        $spreadsheet = IOFactory::load($the_file->getRealPath());
        $sheet        = $spreadsheet->getActiveSheet();
        $row_limit    = $sheet->getHighestDataRow();
        $column_limit = $sheet->getHighestDataColumn();
        $row_range    = range( 2, $row_limit );
        $column_range = range( 'F', $column_limit );
        $data = array();

        $approvedCount = User::where('user_type', 'student')->where('is_approved',1)->count();
       
        foreach ( $row_range as $row ) {
            $dob = '';
            $email = trim($sheet->getCell( 'C' . $row )->getValue());
            if($email != ''){
                if(!in_array($email, $students)){
                    $dob = $sheet->getCell( 'F' . $row )->getValue();
                    $dob = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dob)->format('Y-m-d');
         
                    $first_name = $sheet->getCell( 'A' . $row )->getValue();
                    $last_name = $sheet->getCell( 'B' . $row )->getValue();

                    $user = new User();
                    $user->name = $first_name.' '.$last_name;
                    $user->email = $email;
                    $user->password = null;
                    $user->user_type = 'student';
                    $user->unique_id = 'ST'.($approvedCount+1);
                    $user->is_approved = 1;
                    $user->save();

                    if($user->id){
                        $approvedCount++;
                        $data[] = [
                            'user_id' => $user->id,
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            'phone_number' => $sheet->getCell( 'D' . $row )->getValue(),
                            'gender' => strtolower($sheet->getCell( 'E' . $row )->getValue()),
                            'date_of_birth' => $dob,
                            'address' => $sheet->getCell( 'G' . $row )->getValue(),
                        ];
                    }
                }else{
                    $notFound[] = $email;
                }
            }
        }
        if(!empty($notFound)){
            flash("The following email ids were already saved in the system ( ".implode(', ',$notFound)." )")->warning()->important();
        }

        if(!empty($data)){
            UserDetails::insert($data);
            flash("Successfully uploaded! ")->success()->important();
        }else{
            flash("No data uploaded")->error()->important();
        }
        
        return redirect()->route('student.bulk-create');
    }

    function exportBooking(){
        $data = [];
        if(Session::has('booking_filter')){
            $data = Session::get('booking_filter');
        }
       
        if(isset($data['bookingID']) && $data['bookingID'] != ''){
            $query->where('unique_booking_id', $data['bookingID']);
        }

        $title_search = $teacher_search =  $date_search = null;
        
        if(isset($data['title']) && $data['title'] != '') {
            $title_search = $data['title'];
        }
        if(isset($data['teacher']) && $data['teacher'] != '') {
            $teacher_search = $data['teacher'];
        }
        if(isset($data['date_search']) && $data['date_search'] != '') {
            $date_search = $data['date_search'];
        }

        $query = Bookings::with(['student','student_details','teacher','course_division','slot','cancelledBy','createdBy'])
                ->where('is_deleted',0)
                ->orderBy('id','DESC');
        if(Auth::user()->user_type == 'staff'){
            $query->where('teacher_id', Auth::user()->id);
        }
        if($title_search){
            $query->Where(function ($query) use ($title_search) {
                $query->whereHas('student', function ($query)  use($title_search) {
                    $query->where('users.name', 'LIKE', "%$title_search%")
                        ->orWhere('users.email', 'LIKE', "%$title_search%")
                        ->orWhere('users.unique_id', 'LIKE', "%$title_search%"); 
                });
                $query->orWhereHas('student_details', function ($query)  use($title_search) {
                    $query->where('user_details.phone_number', 'LIKE', "%$title_search%"); 
                });
            }); 
        }
        if($date_search){
            $query->where('booking_date', $date_search);
        }
        if($teacher_search){
            $query->where('teacher_id', $teacher_search);
        }
    
        $bookings = $query->get();
       

        $data_array [] = array("Booking Date","Booking Slot","Student Name","Student Code","Teacher Name","Division","Created By","Attended Status","Cancel Status");
        foreach($bookings as $data_item)
        {  
            $data_array[] = array(
                'Booking Date' => $data_item->booking_date,
                'Booking Slot' => $data_item->slot->slot,
                'Student Name' => $data_item->student->name,
                'Student Code' => $data_item->student->unique_id,
                'Teacher Name' => $data_item->teacher->name,
                'Division' => $data_item->course_division->title,
                'Created By' => $data_item->createdBy->name ?? '',
                'Attended Status' => (($data_item->is_attended) == 1) ? 'Attended' : ((($data_item->is_attended) == 2) ? 'Not Attended' : '-') ,
                'Cancel Status' => ($data_item->is_cancelled == 1) ? 'Cancelled By'.($data_item->cancelledBy->name ?? '') : '-',
            );
        }
        $this->ExportExcel($data_array);
    }

    public function ExportExcel($booking_data){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');
        try {
            $spreadSheet = new Spreadsheet();
            $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
            $spreadSheet->getActiveSheet()->getStyle('1:1')->getFont()->setBold(true);
            $spreadSheet->getActiveSheet()->getStyle('1:1')->getFont()->setSize('13px');
            $spreadSheet->getActiveSheet()->fromArray($booking_data);
            $Excel_writer = new Xls($spreadSheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Bookings_'.date('d-m-Y').'.xls"');
            header('Cache-Control: max-age=0');
            // ob_end_clean();
            $Excel_writer->save('php://output');
            exit();
        } catch (Exception $e) {
            return;
        }
    }

    public function viewStudent(Request $request, $id){
       
        $user = User::with(['user_details','student_all_packages','mock_tests'])
                ->where('user_type', 'student')
                ->where('is_deleted',0)
                ->where('id',$id)->first();
        // echo '<pre>';
        // print_r($user);
        // die;
        return view('admin.students.view',compact('user'));
    }

    public function approveStudentBooking(Request $request){
        $user = User::find($request->id);
        $user->booking_approval = 1;
        $user->save();

        Bookings::where('student_id',$request->id)->where('booking_success',0)->update(['booking_success' => 1]);
        
    }
}
