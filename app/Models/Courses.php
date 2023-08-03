<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'description', 'banner_image', 'is_active', 'is_deleted'];

    public function course_divisions()
    {
        return $this->hasMany(CourseDivisions::class);
    } 
    public function course_packages()
    {
        return $this->hasMany(CoursePackages::class);
    } 
    public function course_classes()
    {
        return $this->hasMany(CourseClasses::class);
    } 
    public function student_packages()
    {
        return $this->hasMany(StudentPackages::class,'course_id','id');
    } 
}
