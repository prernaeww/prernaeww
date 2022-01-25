<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteProduct extends Model {
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $table = 'favorite_products';

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = array('stock');

    public function getStockAttribute()
    {
        $store_product = StoresProduct::where('user_id',$this->store_id)->where('product_id',$this->product_id)->first(); 
        if($store_product){
            return $store_product->stock;
        }
        return 0;
    }

    public function store() {
        return $this->hasOne('App\Models\User', 'id', 'store_id');
    }

    public function product() {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }
}
