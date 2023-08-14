<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_type', 
        'unique_id',
        'name', 
        'email', 
        'email_verified_at', 
        'password', 
        'is_approved', 
        'is_active', 
        'is_deleted'
    ];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }  
    public function user_details()
    {
        return $this->hasOne(UserDetails::class);
    }  

    public function approved()
    {
        return $this->hasMany(User::class)->where('is_approved', 1);
    }

    public function rejected()
    {
        return $this->hasMany(User::class)->where('is_approved', 2);
    }
    // public function teacher_divisions()
    // {
    //     return $this->hasMany(TeacherDivisions::class,'teacher_id','id');
    // }

    public function student_packages()
    {
        return $this->hasMany(StudentPackages::class,'user_id','id')->with(['package'])->where('is_active',1)->where('is_deleted',0);
    } 

    public function teacher_divisions(){
        return $this->hasMany(TeacherDivisions::class,'teacher_id','id')->with(['course_division' => function ($query1) {
            $query1->with(['course_name']);
            $query1->where('is_active', 1);
            }])->where('is_deleted',0);
    }

    public function assigned_teachers(){
        return $this->hasMany(AssignedTeachers::class,'teacher_id','id')->with(['course_division' => function ($query1) {
            $query1->with(['course_name']);
            $query1->where('is_active', 1);
            }])->where('is_deleted',0);
    }

    public function notifications()
    {
        return $this->hasMany(Notifications::class,'user_id','id');
    }
    // public function booking_cancel_by()
    // {
    //     return $this->hasMany(Bookings::class,'cancelled_by','id');
    // }
}
