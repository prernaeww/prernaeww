<?php



namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'cart';
    protected $fillable = [
        'user_id',
        'store_id',
        'order_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
	}

    public function store() {
        return $this->hasOne('App\Models\User', 'id', 'store_id');
    }

    public function cart_products() {
        return $this->hasMany('App\Models\CartProducts', 'cart_id', 'id');
    }

}

