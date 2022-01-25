<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'banner';
    protected $fillable = [
        'user_id',
        'sequence',
        'image',
        'start_date',
        'end_date'
    ];
    public function getImageAttribute($image) {
		return $image == null ? url('/images/default.png') : url('/images/banner/'.$image);
	}
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


     protected $appends = array('date_diff');

    public function user_name(){
        return $this->belongsTo(User::class, 'user_id', 'id'); 
    }

    public function getDateDiffAttribute()
    {
        
        $current = date('Y-m-d');
        $end = \Carbon\Carbon::parse($this->end_date);

        if($current > $end) 
        {
             return 'expired';
        }
        else{

            $to = \Carbon\Carbon::now();
            $from = \Carbon\Carbon::parse($this->end_date);
            $days = $to->diffInDays($from);
            return $days.' Days Left';

        }
    }

}
