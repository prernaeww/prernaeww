<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class School extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'name',
        'status',
        'canteen_id',
        'address',
        'block',
        'area',
        'street'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = array('canteen_name','calendar_constants_month','today_date');

    public function getCanteenNameAttribute()
    {
        $user = User::find($this->canteen_id);  
        return $user->first_name.' '.$user->last_name;  
    }

    public function getCalendarConstantsMonthAttribute()
    {   
        $date1 = date('Y-m-d');
        $date2 = $this->calendar_constants;

        $ts1 = strtotime($date1);
        $ts2 = strtotime($date2);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        $months = (($year2 - $year1) * 12) + ($month2 - $month1);

        // $months = Carbon::createFromDate($this->calendar_constants)->diff(Carbon::now())->format('%m');
        return $months > 0 ? $months : 4;
    }
    public function getTodayDateAttribute()
    {        
        return date('Y-m-d');
    }

    public function holiday() {
        return $this->hasMany('App\Models\SchoolHoliday', 'user_id', 'id');
	}
    public function grades() {
        return $this->hasMany('App\Models\SchoolGrade', 'school_id', 'id');
	}

}
