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
    public function index()
    {
        return view('admin.auth.login');
    }  
      
    public function customLogin(Request $request){
        $request->validate([
            'e_mail' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = array('email' => $request->e_mail, 'password' => $request->password);
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
    
    public function dashboard()
    {
        if(Auth::check()){
            return view('dashboard');
        }
  
        return redirect("login")->withSuccess('You are not allowed to access');
    }
    
    public function signOut() {
        Session::flush();
        Auth::logout();
  
        return Redirect('login');
    }
}