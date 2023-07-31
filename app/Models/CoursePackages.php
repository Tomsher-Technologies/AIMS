<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursePackages extends Model
{
    use HasFactory;

    protected $fillable = ['courses_id', 'package_title', 'duration', 'fees', 'is_active', 'is_deleted', 'created_at'];

    public function course_name(){
    	return $this->belongsTo(Courses::class,'courses_id','id');
    }

    public function package_modules()
    {
        return $this->hasMany(PackageModules::class,'package_id','id');
    } 

    public function active_package_modules(){
        return $this->hasMany(PackageModules::class,'package_id','id')->with(['course_division' => function ($query1) {
            $query1->where('is_active', 1);
            }]);
    }
}
