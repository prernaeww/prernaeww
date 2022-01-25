<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronLog extends Model
{
    use HasFactory;

    protected $fillable = [
         'type',
         'ids',
    ];

     protected $casts = [        
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $table = 'cron_log';
}
