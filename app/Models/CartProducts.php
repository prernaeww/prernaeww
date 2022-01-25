<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartProducts extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'cart_products';
    protected $fillable = [
        'cart_id',
        'product_id',
        'qty'
    ];

    protected $appends = array('stock');

    public function product()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    public function Cart()
    {
        return $this->hasOne('App\Models\Cart', 'id', 'cart_id');
    }

    public function getStockAttribute()
    {
        $cart = Cart::where('id', $this->cart_id)->first();
        if ($cart) {
            $store_product = StoresProduct::where('user_id', $cart->store_id)->where('product_id', $this->product_id)->first();
            if ($store_product) {
                return $store_product->stock;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
}