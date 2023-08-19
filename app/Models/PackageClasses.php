<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageClasses extends Model
{
    use HasFactory;
    protected $fillable = ['package_id', 'class_id', 'is_active', 'is_deleted'];

    public function package(){
    	return $this->belongsTo(CoursePackages::class,'package_id','id');
    }

    public function class(){
    	return $this->belongsTo(CourseClasses::class,'class_id','id');
    }
}
