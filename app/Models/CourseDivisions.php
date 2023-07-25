<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDivisions extends Model
{
    use HasFactory;

    public function course_name(){
    	return $this->belongsTo(Courses::class,'course_id','id');
    }
}
