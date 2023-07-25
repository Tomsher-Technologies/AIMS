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

    public function user(){
    	return $this->belongsTo(User::class,'user_id','id');
    }
    
    public function country_name(){
    	return $this->belongsTo(Countries::class,'country','id');
    }

    public function state_name(){
    	return $this->belongsTo(States::class,'state','id');
    }
    
}
