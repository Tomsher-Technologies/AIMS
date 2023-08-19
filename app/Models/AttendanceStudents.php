<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceStudents extends Model
{
    use HasFactory;
    protected $fillable = ['attend_id', 'student_id', 'status'];

    public function student(){
    	return $this->belongsTo(User::class,'student_id','id');
    }

    public function attendance(){
    	return $this->belongsTo(Attendances::class,'attend_id','id');
    }

}
