<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdersProduct extends Model

{

    use HasFactory;

    protected $table = 'order_products';

    protected $fillable = [
        'order_id',
        'product_id',
        'qty',
        'price'
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



    protected $appends = array('product_name', 'image', 'measurement_name');


    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id')->withTrashed();
    }

    public function measurement()
    {
        return $this->hasOne('App\Models\measurement', 'id', 'measurement_id');
    }

    public function getMeasurementNameAttribute()
    {
        $measurement = Product::where('id', $this->measurement_id)->first();
        if (isset($measurement)) {
            return $measurement['name'];
        }
        return '';
    }




    public function getProductNameAttribute()
    {
        $product = Product::find($this->product_id);
        return $product == null ? '' : $product->name;
    }

    public function getImageAttribute()
    {
        $product = Product::find($this->product_id);
        return $product == null ? '' : $product->image;
    }
}