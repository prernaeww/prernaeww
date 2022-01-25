<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolHoliday extends Model
{
    use HasFactory;
    protected $table = 'school_holiday';
    protected $fillable = [
        'user_id',
        'from_date',
        'to_date',
    ];
}
