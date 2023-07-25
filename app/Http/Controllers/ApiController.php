<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\Countries;
use App\Models\States;
use App\Models\Courses;
use Validator;
use Hash;
use Str;
use File;
use Storage;

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
    
}
