<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseClasses extends Model
{
    use HasFactory;
    protected $fillable = ['module_id', 'course_id', 'class_name', 'order', 'is_mandatory', 'is_active', 'is_deleted'];

    public function course(){
    	return $this->belongsTo(Courses::class,'course_id','id');
    }

    public function course_division(){
    	return $this->belongsTo(CourseDivisions::class,'module_id','id');
    }
    public function packages()
    {
        return $this->hasMany(PackageClasses::class,'class_id','id')->with(['package'])->where('is_deleted',0);
    } 
}
