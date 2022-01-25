<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolGrade extends Model
{
    use HasFactory;
    protected $table = 'school_grades';
    protected $fillable = [
        'school_id',
        'grade_id',
    ];
    protected $appends = array('grade');

    public function getGradeAttribute()
    {
        $grade = Grade::find($this->grade_id);
        return $grade == null ? '' : $grade->name;
        return $grade->name;
    }    
}
