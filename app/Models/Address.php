<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
         'user_id',
         'lat',
         'log',
         'zipcode',
         'city',
         'state',
         'address_type',
         'complete_address',
    ];

     protected $casts = [        
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $table = 'address';
}
