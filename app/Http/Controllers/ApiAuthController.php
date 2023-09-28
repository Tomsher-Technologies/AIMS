<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\Countries;
use App\Models\States;
use App\Models\Courses;
use App\Models\CoursePackages;
use App\Models\StudentPackages;
use App\Models\AssignTeachers;
use App\Models\TeacherSlots;
use App\Models\Bookings;
use App\Models\Remarks;
use App\Models\Notifications;
use App\Models\StudentClasses;
use App\Models\MockTests;
use Validator;
use Hash;
use Str;
use File;
use Storage;
use DB;

class ApiAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

     /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth('api')->attempt($validator->validated())) {
            return response()->json(['status' => false, 'message' => 'Invalid login details', 'data' => []], 401);
        }else{
            if(auth('api')->user()->is_approved == 0){
                return response()->json(['status' => false, 'message' => 'Your account is waiting for admin approval.', 'data' => []], 401);
            }elseif(auth('api')->user()->is_deleted == 1){
                return response()->json(['status' => false, 'message' => 'Your account is Deleted.', 'data' => []], 401);
            }elseif(auth('api')->user()->is_active == 0){
                return response()->json(['status' => false, 'message' => 'Your account is Disabled.', 'data' => []], 401);
            }else{
                return $this->createNewToken($token);
            }
        }
    }

        /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            return response()->json(['status' => false, 'message' => 'The email has already been taken.', 'data' => []  ], 400);
        }
        $user = new User;
        $user->user_type = 'student';
        $user->name = $request->first_name.' '.$request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
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
            $uDetails->date_of_birth = $request->date_of_birth;
            $uDetails->phone_code = $request->phone_code;
            $uDetails->phone_number = $request->phone_number;
            $uDetails->address = $request->address;
            $uDetails->country = $request->country;
            $uDetails->state = $request->state;
            $uDetails->city = $request->city;
            $uDetails->passport = $passportFront;
            $uDetails->profile_image = $profileImage;
            $uDetails->enrollment_form = $enrollmentForm;
            $uDetails->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Thank you for applying for an account. Your account is currently awaiting administrator approval.',
            'data' => []
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth('api')->logout();
        return response()->json(['status' => true,'message' => 'User successfully signed out','data' => []]);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth('api')->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile(Request $request) {
        $user = User::select('users.*','ud.first_name', 'ud.last_name', 'ud.gender', 'ud.date_of_birth', 'ud.phone_code', 'ud.phone_number', 'ud.address', 'ud.country', 'ud.state', 'ud.city', 'ud.passport', 'ud.profile_image', 'ud.enrollment_form')
                    ->leftJoin('user_details as ud','ud.user_id','=','users.id')
                    ->where('users.id', $request->id)
                    ->where('users.is_deleted',0)
                    ->where('users.is_approved',1)
                    ->get();
                    
        if(isset($user[0])){
            $user[0]['passport'] = ($user[0]['passport'] != '') ? asset($user[0]['passport']) : '';
            $user[0]['profile_image'] = ($user[0]['profile_image'] != '') ? asset($user[0]['profile_image']) : '';
            $user[0]['enrollment_form'] = ($user[0]['enrollment_form'] != '') ? asset($user[0]['enrollment_form']) : '';

            $country = $user[0]->user_details->country_name;
            $state = $user[0]->user_details->state_name;
            $user[0]['country_name'] = (!empty($country)) ? $country->name : '';
            $user[0]['state_name'] = (!empty($state)) ? $state->name : '';
            unset($user[0]['user_details']);
            return response()->json([ 'status' => true, 'message' => 'Success', 'data' => $user]);
        }else{
            return response()->json([ 'status' => false, 'message' => 'User details not found.', 'data' => []]);
        }
    }
    
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'status' => true,
            'message' => 'Successfully loggedIn',
            'data' => auth('api')->user(),
            'access_token' => $token,
            'token_type' => 'bearer',
            // 'expires_in' => auth('api')->factory()->getTTL() * 60,
            
        ]); 
    }

    

    // Get all courses list with course divisions
    public function getAllCourses() {
        $courses = Courses::select('*')
                    ->where('is_active',1)
                    ->where('is_deleted',0)
                    ->orderBy('id','DESC')
                    ->get();
                    
        if(isset($courses[0])){
            foreach($courses as $key => $co){
                $courses[$key]['banner_image'] = ( $co['banner_image'] != NULL) ? asset($co['banner_image']) :'';
            }
            return response()->json([ 'status' => true, 'message' => 'Success', 'data' => $courses]);
        }else{
            return response()->json([ 'status' => false, 'message' => 'Details not found.', 'data' => []]);
        }
    }

    // Get course details
    public function getCourseDetails(Request $request) {
        $courses = Courses::select('*')
                    ->where('is_active',1)
                    ->where('is_deleted',0)
                    ->where('id',$request->id)
                    ->get();
                    
        if(isset($courses[0])){
            $courses[0]['banner_image'] = ( $courses[0]['banner_image'] != NULL) ? asset($courses[0]['banner_image']) :'';
            $courses[0]->course_divisions;
            return response()->json([ 'status' => true, 'message' => 'Success', 'data' => $courses]);
        }else{
            return response()->json([ 'status' => false, 'message' => 'Details not found.', 'data' => []]);
        }
    }
    
    // Update user profile details
    public function updateUserData(Request $request){
        $userId         = $request->user_id;
        $first_name     = $request->first_name;
        $last_name      = $request->last_name;
       
        $user = User::find($userId);
        $user->name = $first_name.' '.$last_name;
        $user->save();

        $data = [
            'first_name' => $first_name, 
            'last_name' => $last_name, 
            'gender' => $request->gender, 
            'date_of_birth' => $request->date_of_birth, 
            'phone_code' => $request->phone_code, 
            'phone_number' => $request->phone_number, 
            'address' => $request->address, 
            'country' => $request->country, 
            'state' => $request->state, 
            'city' => $request->city
        ];

        UserDetails::where('user_id',$userId)->update($data);
        return response()->json(['status' => true,'message' => 'User details updated successfully', 'data' => []]);
    }
    
    // Update user profile image
    public function updateProfileImage(Request $request){
        $userId = $request->user_id;

        $userdata = UserDetails::where('user_id', $userId)->get();
      
        if(isset($userdata[0])){
            $presentImage = $userdata[0]['profile_image'];

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
                if($presentImage != '' && File::exists(public_path($presentImage))){
                    unlink(public_path($presentImage));
                }
                $update =  UserDetails::where('user_id', $userId)->update(['profile_image' => $profileImage]);
                return response()->json(['status' => true,'message' => 'User image updated successfully', 'data' => ['profile_image' => asset($profileImage)]]);
            }else{
                return response()->json(['status' => false,'message' => 'Failed to update user image', 'data' => []]);
            }
        }else{
            return response()->json(['status' => false,'message' => 'User not found', 'data' => []]);
        } 
    }

    public function changePassword(Request $request)
    {
        $userId = $request->user_id;
        $user = User::find($userId);
        // print_r($user); die;
        // echo $user->password;
        // die;
        // The passwords matches
        if (!Hash::check($request->get('current_password'), $user->password)){
            return response()->json(['status' => false,'message' => 'Old password is incorrect', 'data' => []]);
        }
 
        // Current password and new password same
        if (strcmp($request->get('current_password'), $request->new_password) == 0){
            return response()->json(['status' => false,'message' => 'New Password cannot be same as your current password.', 'data' => []]);
        }

        $user->password =  Hash::make($request->new_password);
        $user->save();
        return response()->json(['status' => true,'message' => 'Password Changed Successfully', 'data' => []]);
    }

    // Get all packages list
    public function getAllPackages() {
        $packages = CoursePackages::with(['course_name'])->select('*')
                    ->where('is_active',1)
                    ->where('is_deleted',0)
                    ->orderBy('id','DESC')
                    ->get();
                    
        if(isset($packages[0])){
            foreach($packages as $key => $pack){
                $course = (!empty($pack->course_name)) ? $pack->course_name->name : '';
                $banner_image = (!empty($pack->course_name)) ? $pack->course_name->banner_image : '';
                unset($packages[$key]['course_name']);
                $packages[$key]['course_name'] = $course;
                $packages[$key]['banner_image'] = ($banner_image != NULL) ? asset($banner_image) : '';
                $packages[$key]['currency'] = config('constants.default_currency');
            }
            return response()->json([ 'status' => true, 'message' => 'Success', 'data' => $packages]);
        }else{
            return response()->json([ 'status' => false, 'message' => 'Details not found.', 'data' => []]);
        }
        
    }

    // Get course packages list 
    public function getCoursePackages(Request $request) {
        $packages = CoursePackages::with(['course_name'])->select('*')
                    ->where('courses_id', $request->course_id)
                    ->where('is_active',1)
                    ->where('is_deleted',0)
                    ->orderBy('id','DESC')
                    ->get();
                    
        if(isset($packages[0])){
            foreach($packages as $key => $pack){
                $course = (!empty($pack->course_name)) ? $pack->course_name->name : '';
                $banner_image = (!empty($pack->course_name)) ? $pack->course_name->banner_image : '';
                unset($packages[$key]['course_name']);
                $packages[$key]['course_name'] = $course;
                $packages[$key]['banner_image'] = ($banner_image != NULL) ? asset($banner_image) : '';
                $packages[$key]['currency'] = config('constants.default_currency');
            }
            return response()->json([ 'status' => true, 'message' => 'Success', 'data' => $packages]);
        }else{
            return response()->json([ 'status' => false, 'message' => 'Details not found.', 'data' => []]);
        }
    }

    public function getPackageDetails(Request $request) {
        $packages = CoursePackages::with(['course_name','active_package_modules'])->select('*')
                    ->where('id', $request->id)
                    ->where('is_active',1)
                    ->where('is_deleted',0)
                    ->get();
            // dd(DB::getQueryLog());   
        $checkUserPackage = 0;
        if(isset($request->user_id)){
            $checkUserPackage = StudentPackages::where('user_id',$request->user_id)
                                                ->where('package_id',$request->id)
                                                ->where('end_date','>', date('Y-m-d'))
                                                ->where('start_date','<=', date('Y-m-d'))
                                                ->where('is_active',1)
                                                ->where('is_deleted',0)->count();
        }
        
        if(isset($packages[0])){
            $packages[0]['is_user_package'] = ($checkUserPackage == 0) ? 0 : 1;
            foreach($packages as $key => $pack){
                $course = (!empty($pack->course_name)) ? $pack->course_name->name : '';
                $banner_image = (!empty($pack->course_name)) ? $pack->course_name->banner_image : '';
                unset($pack->course_name);
                $packages[$key]['course_name'] = $course;
                $packages[$key]['banner_image'] = ($banner_image != NULL) ? asset($banner_image) : '';
                $packages[$key]['currency'] = config('constants.default_currency');
                $modules = $pack->active_package_modules;
                unset($pack->active_package_modules);
                $divisions = [];
                
                foreach($modules as $mkey => $mod){
                    if($mod->course_division != null){
                        $divisions[$mkey]['module_id'] = $mod->course_division->id;
                        $divisions[$mkey]['module_name'] = $mod->course_division->title;
                        $divisions[$mkey]['module_description'] = $mod->course_division->description;
                    }
                    unset($mod->course_division);
                }
                $packages[$key]['package_modules'] = $divisions;
            }
            // dd(DB::getQueryLog());
            return response()->json([ 'status' => true, 'message' => 'Success', 'data' => $packages]);
        }else{
            return response()->json([ 'status' => false, 'message' => 'Details not found.', 'data' => []]);
        }
    }

    public function getTimeSlots(Request $request){
        $date = $request->date;
        $module_id = $request->module_id;

        $teachers = AssignTeachers::leftJoin('users as us','us.id','=','assign_teachers.teacher_id')
                                    ->where('assign_teachers.is_active',1)
                                    ->where('assign_teachers.is_deleted',0)
                                    ->where('assign_teachers.assigned_date', $date)
                                    ->where('assign_teachers.module_id', $module_id)
                                    ->where('us.is_active',1)
                                    ->where('us.is_deleted',0)->select('us.id','us.name','assign_teachers.id as assign_id')
                                    ->orderBy('us.name','ASC')->get()->toArray();
        $data['teachers'] = $teachers;
        
        if($teachers){
            foreach($teachers as $slot){
                $data['slots'][$slot['id']] = $this->getTeacherSlots($slot['assign_id']);
            }
            return response()->json([ 'status' => true, 'message' => 'Success', 'data' => $data]);
        }else{
            return response()->json(['status'=>false,'message'=>'Time slots are not available for this date','data'=>$data ]);
        } 
    }
    public function getTeacherSlots($id){
        $slots = TeacherSlots::where('assigned_id', $id)->where('is_booked',0)
                                ->where('is_deleted',0)->select('id','slot')
                                ->orderBy('id','ASC')->get()->toArray();
        return $slots;
    }

    public function booking(Request $request){
        $student_id = $request->student_id;
        $teacher_id = $request->teacher_id;
        $module_id = $request->module_id;
        $slot_id = $request->slot_id;
        $booking_date = $request->booking_date;

        $user = User::find($student_id);
        if($user->booking_approval == 1){
            if($student_id != '' && $teacher_id != '' && $module_id != '' && $slot_id != '' && $booking_date != '' ){
                $book = new Bookings();
                $book->student_id = $student_id;
                $book->teacher_id = $teacher_id;
                $book->module_id = $module_id;
                $book->slot_id = $slot_id;
                $book->booking_date = $booking_date;
                $book->created_by = $student_id;
                $book->save();
                $data = [];
                if($book->id){
                    TeacherSlots::where('id',$slot_id)->update(['is_booked' => 1]);
                    $bookData = Bookings::with(['course_division','slot','teacher'])->find($book->id);
                    $data['module'] = $bookData->course_division->title;
                    $data['teacher'] = $bookData->teacher->name;
                    $data['slot'] = $bookData->slot->slot;
                    $data['date'] = $bookData->booking_date;

                    return response()->json([ 'status' => true, 'message' => 'Successfully Booked', 'data' => $data]);
                }else{
                    return response()->json([ 'status' => false, 'message' => 'Booking failed', 'data' => []]);
                }
            }else{
                return response()->json([ 'status' => false, 'message' => 'Booking failed', 'data' => []]);
            }
        }else{
            return response()->json([ 'status' => false, 'message' => 'Booking failed! Contact admin for booking approval.', 'data' => []]);
        }
    }

    public function studentsBookings(Request $request){
        $bookings = Bookings::leftJoin('course_divisions as cd','cd.id','=','bookings.module_id')
                            ->leftJoin('courses as c','c.id','=','cd.courses_id')
                            ->leftJoin('teacher_slots as slot','slot.id','=','bookings.slot_id')
                            ->leftJoin('users as teach','teach.id','=','bookings.teacher_id')
                            ->leftJoin('users as cancel','cancel.id','=','bookings.cancelled_by')
                            ->where('bookings.student_id', $request->user_id)
                            ->select('bookings.id as booking_id','bookings.booking_date','c.name as course_name','cd.title as module_name','teach.name as teacher_name','bookings.is_cancelled','slot.slot','bookings.created_at','bookings.is_attended','bookings.cancelled_by','cancel.name as cancelled_user')
                            ->orderBy('bookings.id','DESC')
                            ->get();

        $course = StudentPackages::where('user_id',$request->user_id)
                                ->where('end_date','>', date('Y-m-d'))
                                ->where('start_date','<=', date('Y-m-d'))
                                ->where('is_active',1)
                                ->where('is_deleted',0)->pluck('package_id')->toArray();

        if(isset($bookings[0])){
            return response()->json([ 'status' => true, 'message' => 'Success', 'data' => $bookings,'package_id' => $course[0] ?? 0]);
        }else{
            return response()->json(['status'=>false,'message'=>'No bookings found','data' => [] ,'package_id' => $course[0] ?? 0]);
        } 
    }

    public function cancelBooking(Request $request){
        $cancel = Bookings::findorfail($request->booking_id);
        $slot_id = $cancel->slot_id;
        $cancel->update(['is_cancelled'=>1, 'cancelled_by' => $request->user_id]);
        TeacherSlots::where('id', '=', $slot_id)->update(['is_booked'=>0]);
        if($cancel->is_cancelled == 1){
            return response()->json(["status" => true, "message"=>"Booking cancelled successfully",'data' => []]);
        }else{
            return response()->json(["status"=>false,"message"=>"Cancellation failed!",'data' => [] ]);
        }
    }

    public function saveRemarks(Request $request){
        $remark = $request->remark;
        $user_id = $request->user_id;

        if($remark != '' && $user_id != ''){
            $rem = new Remarks();
            $rem->remarks = $remark;
            $rem->student_id = $user_id;
            $rem->save();
    
            if($rem->id){
                return response()->json(["status" => true, "message"=>"Message sent successfully! Thank you.",'data' => []]);
            }else{
                return response()->json(["status"=>false,"message"=>"Soemthing went wrong!",'data' => [] ]);
            }
        }else{
            return response()->json(["status"=>false,"message"=>"Soemthing went wrong!",'data' => [] ]);
        }
    }

    public function getClasses(Request $request){
        $user_id = $request->user_id;

        $course = StudentPackages::where('user_id',$user_id)->get();
    }

    public function notifications(Request $request){
        $user_id = $request->user_id;
        $notifications = Notifications::where('user_id', $user_id)
                                    ->where('is_deleted',0)
                                    ->orderBy('id', 'DESC')
                                    ->select('id','content','is_read','created_at')->get();
        if(!empty($notifications[0])){
            Notifications::where('user_id', $user_id)->update(['is_read' => 1]);
            return response()->json(["status" => true, "message"=>"Success",'data' => $notifications]);
        }else{
            return response()->json(["status" => false,'message'=>'No data found!', 'data' => []]);
        }
    }

    public function unreadNotifications(Request $request){
        $user_id = $request->user_id;
        $notifications = Notifications::where('user_id', $user_id)
                                    ->where('is_deleted',0)
                                    ->where('is_read',0)
                                    ->count();
        return response()->json(["status" => true, "message"=>"Success",'data' => $notifications]);
    }

    public function getStudentClasses(Request $request){
        $user_id = $request->user_id;
        $course = StudentPackages::leftJoin('courses as co', 'co.id', '=', 'student_packages.course_id')
                                ->leftJoin('course_packages as cp', 'cp.id', '=', 'student_packages.package_id')
                                ->where('student_packages.user_id',$user_id)
                                ->where('student_packages.is_active',1)->where('student_packages.is_deleted',0)
                                ->select('cp.package_title as package_name','co.id as course_id','co.name as course_name','co.description as course_description','co.banner_image')
                                ->get();
    
        if(!empty($course)){
            if(isset($course[0])){
                $course[0]['banner_image'] = ( $course[0]['banner_image'] != NULL) ? asset($course[0]['banner_image']) :'';
            }
            $data['course'] = $course;

            $data['classes'] = StudentClasses::leftJoin('course_classes as cc','cc.id','=','student_classes.class_id')
                                        ->leftJoin('course_divisions as cd','cc.module_id','=','cd.id')
                                        ->where('cc.is_active',1)->where('cc.is_deleted',0)
                                        ->where('student_classes.user_id', $user_id)
                                        ->select('student_classes.id','cc.class_name','cd.title','student_classes.is_attended','student_classes.created_at')
                                        ->orderBy('student_classes.is_active','DESC')
                                        ->orderBy('cc.order','ASC')
                                        ->get();
            $allClass = $data['classes']->toArray();

            $attended = array_filter($allClass, function($elem){
                return $elem['is_attended'] === 1;
            });
            if(!empty($attended)){
                $progress = (count($attended)/count($allClass))*100;
            }else{
                $progress = 0;
            }
            
            $data['progress'] = round($progress);
                                    
            return response()->json(["status" => true, "message"=>"Success",'data' => $data]);
        }else{
            return response()->json(["status" => false,'message'=>'No data found!', 'data' => []]);
        }
    }

    public function updateClassStatus(Request $request){
        $attended = StudentClasses::where('user_id', $request->user_id)->where('id', $request->class_id)
                                ->update(['is_attended' => 1, 'attended_date' => date('Y-m-d')]);
        if($attended){
            return response()->json(["status" => true, "message"=>"Updated successfully",'data' => []]);
        }else{
            return response()->json(["status"=>false,"message"=>"Updation failed!",'data' => [] ]);
        }
    }

    public function studentMockTests(Request $request){
        $user_id = $request->user_id;
        $mock_tests = MockTests::where('student_id', $user_id)
                                    ->where('is_deleted',0)
                                    ->orderBy('test_date', 'DESC')
                                    ->select('test_date', 'student_id', 'listening_a', 'listening_b', 'listening_c', 'listening_total', 'reading_a', 'reading_b', 'reading_c', 'reading_total')->get();
        if(!empty($mock_tests[0])){
            return response()->json(["status" => true, "message"=>"Success",'data' => $mock_tests]);
        }else{
            return response()->json(["status" => false,'message'=>'No data found!', 'data' => []]);
        }
    }

}


