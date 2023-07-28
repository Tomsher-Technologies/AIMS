<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CustomAuthController extends Controller
{
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
    }

    public function index()
    {
        return view('admin.auth.login');
    }  
    private function validator(Request $request)
    {
        //validation rules.
        $rules = [
            'email'    => 'required|email|exists:users|min:5|max:191',
            'password' => 'required|string|min:4|max:255',
        ];

        //custom validation error messages.
        $messages = [
            'email.exists' => 'These credentials do not match our records.',
        ];

        //validate the request.
        $request->validate($rules,$messages);
    }
      
    public function customLogin(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = array('email' => $request->email, 'password' => $request->password);
        if (Auth::attempt($credentials)) {
            if(Auth::user()->user_type != "student"){
                return redirect()->route('admin.dashboard');
            }else{
                auth()->guard()->logout();
       
                $request->session()->invalidate();

                $request->session()->regenerateToken();
                return back()->withInput()->with('status', 'You are not allowed to access!');
            }
        }
  
        return redirect()
            ->back()
            ->withInput()
            ->with('status', 'These credentials do not match our records.');
    }
    
    private function loginFailed(){
        return redirect()
            ->back()
            ->withInput()
            ->with('status', 'These credentials do not match our records.');
    }

 
    public function signOut() {
        Session::flush();
        Auth::logout();

        return redirect()->route('login');
    }
}