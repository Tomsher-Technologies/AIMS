<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherDivisions extends Model
{
    use HasFactory;
   
    protected $fillable = ['teacher_id', 'module_id', 'created_at'];

    public function teacher(){
    	return $this->belongsTo(User::class,'teacher_id','id');
    }

    public function course_division(){
    	return $this->belongsTo(CourseDivisions::class,'module_id','id');
    }
}
