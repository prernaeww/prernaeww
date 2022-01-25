<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{

    use HasFactory;
    protected $table = 'variants';
    protected $fillable = [
        'product_id',
        'quantity',
        'measurement_id',
        'picture',
        'price',
        'business_price'
    ];

    protected $appends = array('measurement', 'discount_amount', 'business_discount_amount');

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

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }
    
    public function getPictureAttribute($picture) {
        return $picture == null ? url('/images/default.png') : url('/images/product/'.$picture);
    }

    public function getMeasurementAttribute()
    {
        $measurement = Measurement::where('id',$this->measurement_id)->first(); 
        if(isset($measurement)){
            return $measurement['name'];
        }
        return '';
    }
    public function getDiscountAmountAttribute()
    {
        $product = Product::find($this->product_id); 
        if($product->discount > 0){
            $discount = ($product->discount / 100) * $this->price;
            $discount_amount = $this->price - $discount;
            return number_format((float)$discount_amount, 2, '.', '');

        }
        return '0';
    }
    public function getBusinessDiscountAmountAttribute()
    {
        $product = Product::find($this->product_id); 
        if($product->business_discount > 0){
            $business_discount = ($product->business_discount / 100) * $this->business_price;
            $business_discount_amount = $this->business_price - $business_discount;
            return number_format((float)$business_discount_amount, 2, '.', '');
        }
        return '0';
    }

}
