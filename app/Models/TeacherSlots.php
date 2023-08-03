<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherSlots extends Model
{
    use HasFactory;

    protected $fillable = ['assigned_id', 'slot', 'is_booked', 'is_deleted'];

    public function teacher_assigned(){
    	return $this->belongsTo(AssignTeachers::class,'assigned_id','id');
    }

}
