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
            $user[0]['country_name'] = $user[0]->user_details->country_name->name;
            $user[0]['state_name'] = $user[0]->user_details->state_name->name;
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
        } 
        UserDetails::where('user_id', $userId)->update(['profile_image' => $profileImage]);
        return response()->json(['status' => true,'message' => 'User image updated successfully', 'data' => ['profile_image' => asset($profileImage)]]);
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
            return response()->json(['status' => false,'message' => 'Current Password is Invalid', 'data' => []]);
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
                $course = $pack->course_name->name;
                $banner_image = $pack->course_name->banner_image;
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
                $course = $pack->course_name->name;
                $banner_image = $pack->course_name->banner_image;
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
                                                ->where('is_active',1)
                                                ->where('is_deleted',0)->count();
        }
        
        if(isset($packages[0])){
            $packages[0]['is_user_package'] = ($checkUserPackage == 0) ? 0 : 1;
            foreach($packages as $key => $pack){
                $course = $pack->course_name->name;
                $banner_image = $pack->course_name->banner_image;
                unset($pack->course_name);
                $packages[$key]['course_name'] = $course;
                $packages[$key]['banner_image'] = ($banner_image != NULL) ? asset($banner_image) : '';
                $packages[$key]['currency'] = config('constants.default_currency');
                $modules = $pack->active_package_modules;
                unset($pack->active_package_modules);
                $divisions = [];
                
                foreach($modules as $mkey => $mod){
                    if($mod->course_division != null){
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
}


