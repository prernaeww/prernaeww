<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class MealCategory extends Model
{

    use HasFactory;
    protected $table = 'meal_categories';
    protected $fillable = [
        'meal_id',
        'category_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends = array('meal_name','category_name','category_image');

    public function getMealNameAttribute()
    {
        $meal = Meal::find($this->meal_id);  
        return $meal->name;  
    }
    public function getCategoryNameAttribute()
    {
        $category = Category::find($this->category_id);  
        return $category->name;  
    }
    public function getCategoryImageAttribute()
    {
        $category = Category::find($this->category_id);  
        return $category->image;  
    }
}
