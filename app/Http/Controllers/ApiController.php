<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\Countries;
use App\Models\States;
use App\Models\Courses;
use Carbon\Carbon; 
use Validator;
use Hash;
use Str;
use File;
use Storage;
use Mail;
use DB;

class ApiController extends Controller
{
    public function __construct() {
       
    }

    // Get all countries
    public function getCountries(){
        $countries = Countries::select('id','name')->orderBy('name','ASC')->get();
        return response()->json([ 'status' => true, 'message' => 'Success', 'data' => $countries]);
    }

    // Get all states and states based on the passed country_id 
    public function getCountryStates(Request $request){
        $query = States::select('*');
        if(isset($request->country_id)){
            $query->where('country_id', $request->country_id);
        }
        $states = $query->orderBy('name','ASC')->get();
        return response()->json([ 'status' => true, 'message' => 'Success', 'data' => $states]);
    }
    
    public function forgotPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
        ]);
        if($validator->fails()){
            return response()->json(['status' => false, 'message' => 'The selected email is not found in our system.', 'data' => []  ], 400);
        }
        $token = Str::random(64);
  
        DB::table('password_resets')->insert([
            'email' => $request->email, 
            'token' => $token, 
            'created_at' => Carbon::now()
          ]);

        Mail::send('admin.email.forgetPassword_app', ['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password');
        });
        return response()->json([ 'status' => true, 'message' => 'We have e-mailed your password reset link!', 'data' => []]);
    }
}
