<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Meal extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'meals';
    protected $fillable = [
        'name',
        'canteen_id',
        'image',
        'price',
        'description',
        'from_date',
        'to_date',
        'kitchen_id',
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

    protected $appends = array('canteen_name','month_price');

    public function getCanteenNameAttribute()
    {
        $user = User::find($this->canteen_id);  
        return $user->first_name.' '.$user->last_name;  
    }
    public function getImageAttribute($image) {
		return $image == null ? '/images/default.png' : env('AWS_S3_MODE').'/meal/'.$image;
	}
    public function getPriceAttribute($price) {
		return number_format((float)$price, 2, '.', '');
	}
    public function getMonthPriceAttribute($price) {
        return number_format((float)$this->price*22, 2, '.', '');
    }


    public function category() {
        return $this->hasMany('App\Models\MealCategory', 'meal_id', 'id');
	}
}
