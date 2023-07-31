<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageModules extends Model
{
    use HasFactory;
    protected $fillable = ['package_id', 'module_id', 'created_at'];

    public function package(){
    	return $this->belongsTo(CoursePackages::class,'package_id','id');
    }

    public function course_division(){
    	return $this->belongsTo(CourseDivisions::class,'module_id','id');
    }
}
