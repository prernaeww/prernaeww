<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'item_code',
        'name',
        'quantity',
        'measurement_id',
        'category_id',
        'family_id',
        'age',
        'proof',
        'previous_price_retail',
        'current_price_retail',
        'previous_price_business',
        'current_price_business',
        'retail_discount',
        'business_discount',
        'image'
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

    protected $appends = array('category_name', 'measurement_name', 'family_name');


    public function getCategoryNameAttribute()
    {
        $category = Category::find($this->category_id);
        return isset($category) ? $category->name : '';
    }

    public function getMeasurementNameAttribute()
    {
        $measurement = Measurement::find($this->measurement_id);
        return isset($measurement) ? $measurement->name : '';
    }

    public function getFamilyNameAttribute()
    {
        $family = Family::find($this->family_id);
        return isset($family) ? $family->name : '';
    }

    public function getImageAttribute($image) {
        return $image == null ? url('/images/default.svg') : url('/images/product/'.$image);
    }

    public function getCurrentPriceRetailAttribute($current_price_retail) {
        return number_format((float)$current_price_retail, 2, '.', '');
    }

    public function getCurrentPriceBusinessAttribute($current_price_business) {
        return number_format((float)$current_price_business, 2, '.', '');
    }

    public function getPreviousPriceRetailAttribute($previous_price_retail) {
        return number_format((float)$previous_price_retail, 2, '.', '');
    }

    public function getPreviousPriceBusinessAttribute($previous_price_business) {
        return number_format((float)$previous_price_business, 2, '.', '');
    }

    public function category(){   
        return $this->belongsTo(Category::class, 'category_id', 'id');  
    }

    public function measurement(){   
        return $this->belongsTo(Measurement::class, 'measurement_id', 'id');  
    }

    public function family(){   
        return $this->belongsTo(Family::class, 'family_id', 'id');  
    }

    public function stores(){   
        return $this->hasMany(StoresProduct::class, 'product_id', 'id');  
    }

}
