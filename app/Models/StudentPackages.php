<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPackages extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'course_id', 'package_id', 'start_date', 'end_date', 'fee_pending', 'due_date', 'is_active', 'is_deleted'];

    public function package(){
    	return $this->belongsTo(CoursePackages::class,'package_id','id');
    }

    public function course(){
    	return $this->belongsTo(Courses::class,'course_id','id');
    }

    public function student(){
    	return $this->belongsTo(User::class,'user_id','id');
    }

    public function classes()
    {
        return $this->hasMany(StudentClasses::class,'student_package_id','id')->with(['class_details']);
    }
    
}
