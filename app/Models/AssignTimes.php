<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignTimes extends Model
{
    use HasFactory;

    protected $fillable = ['assign_id', 'start_time', 'end_time', 'is_active'];

    public function assignTeacher(){
    	return $this->belongsTo(AssignTeachers::class,'assign_id','id');
    }
}
