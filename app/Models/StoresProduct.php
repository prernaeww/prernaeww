<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoresProduct extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'store_products';
    protected $fillable = [
        'user_id',
        'product_id',
        'stock'
    ];

    protected $appends = array('product_code', 'store_name','product_name','measurement_name');

    public function product() {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    public function store()
    {
      return $this->hasOne('App\Models\User', 'id', 'user_id');
    } 

    public function measurement()
    {
      return $this->hasOne('App\Models\measurement', 'id', 'measurement_id');
    } 

    public function getMeasurementNameAttribute()
    {
        $measurement = Product::where('id',$this->measurement_id)->first(); 
        if(isset($measurement)){
            return $measurement['name'];
        }
        return '';
    }

    public function getProductCodeAttribute()
    {
        $product = Product::where('id',$this->product_id)->first(); 
        if(isset($product)){
            return $product['item_code'];
        }
        return '';
    }
    public function getProductNameAttribute()
    {
        $product = Product::where('id',$this->product_id)->first(); 
        if(isset($product)){
            return $product['name'];
        }
        return '';
    }

    public function getStoreNameAttribute()
    {
        $store = User::where('id',$this->user_id)->first(); 
        if(isset($store)){
            return $store['first_name'];
        }
        return '';
    }

}
