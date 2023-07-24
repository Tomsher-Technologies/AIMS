<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'first_name', 
        'last_name', 
        'gender', 
        'date_of_birth', 
        'phone_code', 
        'phone_number', 
        'address', 
        'country', 
        'state', 
        'city', 
        'passport_front', 
        'passport_back', 
        'profile_image', 
        'enrollment_form'
    ];

    
}
