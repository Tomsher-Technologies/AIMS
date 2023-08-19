<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockTests extends Model
{
    use HasFactory;

    protected $fillable = ['test_date', 'student_id', 'listening_a', 'listening_b', 'listening_c', 'listening_total', 'reading_a', 'reading_b', 'reading_c', 'reading_total', 'is_bulk', 'is_deleted'];

    public function student(){
    	return $this->belongsTo(User::class,'student_id','id');
    }
}
