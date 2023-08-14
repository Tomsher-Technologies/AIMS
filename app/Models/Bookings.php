<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookings extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'teacher_id', 'module_id', 'slot_id', 'booking_date', 'is_cancelled', 'cancelled_by', 'is_active', 'is_deleted'];

    public function student(){
    	return $this->belongsTo(User::class,'student_id','id');
    }

    public function teacher(){
    	return $this->belongsTo(User::class,'teacher_id','id');
    }

    public function course_division(){
    	return $this->belongsTo(CourseDivisions::class,'module_id','id');
    }
    public function slot()
    {
        return $this->belongsTo(TeacherSlots::class,'slot_id','id');
    } 

    public function cancelledBy(){
    	return $this->belongsTo(User::class,'cancelled_by','id');
    }

    public function createdBy(){
    	return $this->belongsTo(User::class,'created_by','id');
    }
}
