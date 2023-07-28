<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Courses;
use Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }

    public function index(){
        // $students = User::where('user_type','student')->withCount(['approved', 'rejected'])->get();
        // echo '<pre>';
        // print_r($students);
        // die;

        $total_students = User::where('user_type','student')->count();
        $approved_students = User::where('user_type','student')->where('is_approved', 1)->count();
        $rejected_students = User::where('user_type','student')->where('is_approved', 2)->count();
        return  view('admin.dashboard',compact('total_students','approved_students','rejected_students'));
    }

    public function getAllCourses(){
        $courses = Courses::select('*')
                    ->orderBy('id','DESC')
                    ->get();
        return  view('admin.courses.index',compact('courses'));
    }
   
}
