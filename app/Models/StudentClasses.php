<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentClasses extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'student_package_id', 'course_package_id', 'class_id', 'start_date', 'end_date', 'is_attended', 'is_active'];

    public function package(){
    	return $this->belongsTo(CoursePackages::class,'course_package_id','id');
    }

    public function student_package(){
    	return $this->belongsTo(StudentPackages::class,'student_package_id','id');
    }

    public function student(){
    	return $this->belongsTo(User::class,'user_id','id');
    }
}
