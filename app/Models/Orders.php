<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

use DB;

use CommonHelper;

class Orders extends Model

{

    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'id',
        'user_id',
        'store_id',
        'cart_id',
        'sub_total',
        'total',
        'tax',
        'pickup_method',
        'transaction_id',
        'gateway_trans_id',
        'name',
        'number',
        'pickup_notes',
        'vehicle_description',
        'reached',
        'status'
    ];



    /**

     * The attributes that should be hidden for arrays.

     *

     * @var array

     */

    protected $hidden = [

        'updated_at'

    ];



    protected $appends = array('store_name','customer_name','order_on_formatted','current_status','store_name','reached_on_formatted','user_type','board_name','board_id','phone_formatted', 'status_string');



    public function getStoreNameAttribute()

    {

        $user = User::find($this->store_id);  
        return $user ? $user->first_name : '';  

    }

    public function getPhoneFormattedAttribute()
    {
        $user = User::find($this->user_id);
        if (isset($user->phone) && $user->phone != "") {
            $phone = CommonHelper::SetPhoneFormat($user->phone);
            return $phone;
        }else{
            return '';
        }
    }

    public function getBoardNameAttribute()

    {

        $user = User::find($this->store_id);  
        if($user)
        {
            $board = User::find($user->parent_id);  
            if($board)
            {
                return $board->first_name;  

            }
        }
        return '';

    }

    public function getBoardIdAttribute()

    {

        $user = User::find($this->store_id);  
        return isset($user) ? $user->parent_id : '';

    }



    public function getStoreAddressAttribute()

    {

        $user = User::find($this->store_id);  

        return $user->address;  

    }
     public function getUserTypeAttribute()

    {

        $user = DB::table('users')->whereId($this->user_id)->first();  

        if(isset($user)){

            return $user->user_type;   

        }else{

            return $this->user_id;   

        }

    }


    public function getCustomerNameAttribute()

    {

        $user = DB::table('users')->whereId($this->user_id)->first();  

        if(isset($user)){

            return $user->first_name.' '.$user->last_name;   

        }else{

            return $this->user_id;   

        }

    }

    public function getOrderOnFormattedAttribute()

    {

        if(isset($this->created_at)){

            $created_at_obj = Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at);

            return $created_at_obj->format('m-d-Y h:i A');

        }else{

            return '';

        }

    }

     public function getReachedOnFormattedAttribute()

    {

        if(isset($this->reached)){

            $reached = Carbon::createFromFormat('Y-m-d H:i:s', $this->reached);

            return $reached->format('m-d-Y h:i A');

        }else{

            return '';

        }

    }



    public function getCurrentStatusAttribute()

    {
        $order_status = config('app.order_status');
        return $order_status[$this->status];
    }

    public function getStatusStringAttribute()

    {
        if($this->status == '0'){
            return "Your payment is pending";
        }elseif($this->status == '1'){
            return "Next your order will be accepted by the store and you will be notified";
        }elseif($this->status == '2'){
            return "Once your order is ready to be picked up you will be notified";
        }elseif($this->status == '3'){
            return "Order rejected by store";
        }elseif($this->status == '4'){
            return "When you reached at store location please press 'Button' to notify store manager";
        }elseif($this->status == '5'){
            return "Store will reach you out soon";
        }elseif($this->status == '6'){
            return "Order delivered";
        }elseif($this->status == '7'){
            return "Order failed";
        }else{
            return "-";
        }
    }



    public function customer() {

        return $this->hasOne('App\Models\User', 'id', 'customer_id');

	}
    public function product() {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
        // return $this->hasMany('App\Models\Product', 'id', 'product_id');
    }

    public function order_products()
    {
        return $this->hasMany('App\Models\OrdersProduct', 'order_id', 'id');
    }

    public function store()
    {
        return $this->belongsTo('App\Models\User', 'store_id', 'id');
    }

}

