<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronAddClasses extends Model
{
    use HasFactory;
    protected $fillable = ['package_id', 'class_id', 'is_added'];
}
