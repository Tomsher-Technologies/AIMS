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
use Auth;
use Validator;
use Storage;
use Str;
use File;
use Hash;
use DB;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }

    public function getAllStudents(Request $request){
        $title_search = $course_search =  $package_search = null;
        
        if ($request->has('title')) {
            $title_search = $request->title;
        }
        if ($request->has('course')) {
            $course_search = $request->course;
        }
        if ($request->has('package')) {
            $package_search = $request->package;
        }

        $query = User::with(['user_details','student_packages'])
                ->where('user_type', 'student')
                ->where('is_deleted',0)
                ->orderBy('id','DESC');

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
        return  view('admin.students.index',compact('students','package','courses','title_search','course_search','package_search'));
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
            'phone_number' => 'required',
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

            $packageModules= PackageModules::where('package_id', $request->course_package)
                                            ->where('is_deleted',0)->pluck('module_id')->toArray();
            if(!empty($packageModules)){
                $classes = CourseClasses::whereIn('module_id', $packageModules)
                                        ->where('is_active',1)->where('is_deleted',0)->pluck('id')->toArray();
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
            'phone_number' => 'required',
            'course' => 'required',
            'gender' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'course_package' => 'required'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $user = User::with(['user_details','student_packages'])->findOrFail($id);

        $currentStudentPack = (isset($user->student_packages[0])) ? $user->student_packages[0]->id : '';
        $currentPackageId =  (isset($user->student_packages[0])) ? $user->student_packages[0]->package_id : '';
        $currentCourse =  (isset($user->student_packages[0])) ? $user->student_packages[0]->course_id : '';
        
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

            $packageModules= PackageModules::where('package_id', $request->course_package)
                                            ->where('is_deleted',0)->pluck('module_id')->toArray();
            if(!empty($packageModules)){
                if($currentStudentPack != ''){
                    StudentClasses::where('student_package_id',$currentStudentPack)->update(['is_active' => 0]);
                }
                $classes = CourseClasses::whereIn('module_id', $packageModules)
                                        ->where('is_active',1)->where('is_deleted',0)->pluck('id')->toArray();
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
        }else{
            $pack = StudentPackages::find($currentStudentPack);
            $pack->start_date = $request->start_date;
            $pack->end_date = $request->end_date;
            $pack->fee_pending = $request->fee_pending;
            $pack->due_date = ($request->fee_pending != 0) ? $request->due_date : '';
            $pack->save();
            $student_package_id = $pack->id;
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
        $title_search = $course_search =  $package_search = null;
        
        if ($request->has('title')) {
            $title_search = $request->title;
        }
        if ($request->has('course')) {
            $course_search = $request->course;
        }
        if ($request->has('package')) {
            $package_search = $request->package;
        }

        $query = Bookings::with(['student','teacher','course_division','slot'])
                ->where('is_deleted',0)
                ->orderBy('id','DESC');

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
        

        $bookings = $query->paginate(10);
        $teacher = User::where('user_type', 'staff')->where('is_deleted',0)->orderBy('name','ASC')->get();

        $package = CoursePackages::leftJoin('courses as co','course_packages.courses_id','=','co.id')
                        ->select('course_packages.id', 'course_packages.package_title')
                        ->where('co.is_deleted', 0)
                        ->where('course_packages.is_deleted',0)
                        ->orderBy('package_title','ASC')
                        ->get();
        return  view('admin.bookings.index',compact('bookings','package','teacher','title_search','course_search','package_search'));
    }
  
}
