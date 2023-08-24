<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermissions extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 
        'permission_id',
        'is_active'
    ];

    public function user(){
    	return $this->belongsTo(User::class,'user_id','id');
    }

    public function permission(){
    	return $this->belongsTo(Permissions::class,'permission_id','id');
    }
}
