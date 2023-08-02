<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDivisions extends Model
{
    use HasFactory;
    protected $fillable = ['courses_id', 'title', 'description', 'is_active'];

    public function course_name(){
    	return $this->belongsTo(Courses::class,'courses_id','id');
    }

    public function packages()
    {
        return $this->hasMany(PackageModules::class,'module_id','id');
    }
    public function course_classes()
    {
        return $this->hasMany(CourseClasses::class);
    } 

    public function teacher_divisions()
    {
        return $this->hasMany(TeacherDivisions::class,'module_id','id');
    }

    public function teacher_assigned()
    {
        return $this->hasMany(AssignedTeachers::class,'module_id','id');
    }
}

