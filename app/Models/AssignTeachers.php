<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignTeachers extends Model
{
    use HasFactory;

    protected $fillable = ['teacher_id', 'module_id', 'assigned_date', 'start_time', 'end_time', 'time_interval', 'is_active', 'is_deleted'];

    public function teacher(){
    	return $this->belongsTo(User::class,'teacher_id','id');
    }

    public function course_division(){
    	return $this->belongsTo(CourseDivisions::class,'module_id','id');
    }
}
