<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendances extends Model
{
    use HasFactory;
   
    protected $fillable = [ 'attend_date', 'class_id', 'course_id', 'division_id'];

    public function course(){
    	return $this->belongsTo(Courses::class,'course_id','id');
    }

    public function course_division(){
    	return $this->belongsTo(CourseDivisions::class,'division_id','id');
    }

    public function class(){
    	return $this->belongsTo(CourseClasses::class,'class_id','id');
    }

    public function students()
    {
        return $this->hasMany(AttendanceStudents::class,'attend_id','id');
    } 
}
