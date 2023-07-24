<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\Countries;
use App\Models\States;
use Validator;
use Hash;
use Str;
use Storage;

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
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['status' => false, 'message' => 'Invalid login details', 'data' => []], 401);
        }else{
            if(auth()->user()->is_approved == 0){
                return response()->json(['status' => false, 'message' => 'Your account is waiting for admin approval.', 'data' => []], 401);
            }elseif(auth()->user()->is_deleted == 1){
                return response()->json(['status' => false, 'message' => 'Your account is Deleted.', 'data' => []], 401);
            }elseif(auth()->user()->is_active == 0){
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
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->user());
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
            'data' => auth()->user(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            
        ]);
    }

    public function getCountries(){
        $countries = Countries::select('id','name')->orderBy('name','ASC')->get();
        return response()->json([ 'status' => true, 'message' => 'Success', 'data' => $countries]);
    }

    public function getCountryStates(Request $request){
        $query = States::select('*');
        if(isset($request->country_id)){
            $query->where('country_id', $request->country_id);
        }
        $states = $query->orderBy('name','ASC')->get();
        return response()->json([ 'status' => true, 'message' => 'Success', 'data' => $states]);
    }
}


