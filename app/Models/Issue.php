<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'name'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

	protected $table = 'issue';
}
