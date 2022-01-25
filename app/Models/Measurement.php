<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Measurement extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'measurements';
    protected $fillable = [
        'id',
        'name',
    ];
    
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
