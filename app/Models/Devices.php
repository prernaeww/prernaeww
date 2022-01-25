<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devices extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'token',
        'type',
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

}
