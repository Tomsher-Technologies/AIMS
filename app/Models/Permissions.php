<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 
        'title'
    ];

    public function user_permissions()
    {
        return $this->hasMany(UserPermissions::class,'permission_id','id');
    }
}
