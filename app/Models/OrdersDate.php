<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use CommonHelper;
class OrdersDate extends Model
{
    use HasFactory;
    protected $table = 'order_dates';
    protected $fillable = [
        'order_id',
        'date',
        'day',
        'description',
        'issue_id',
        'status'
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
    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    protected $appends = array('issue','report_show','issuer_name','meal_name','cancel_edit_show');

    public function getIssueAttribute()
    {
        if(isset($this->issue_id) && $this->issue_id != 0){
            $issue = Issue::find($this->issue_id);  
            return $issue == null ? '' : $issue->name;  
        }else{
            return "";
        }
    }
    public function getIssuerNameAttribute()
    {
        if(isset($this->order_id) && $this->order_id != 0){
            $order = Orders::find($this->order_id);  
            return $order == null ? '' : $order->customer_name;
        }else{
            return "";
        }
    }
    public function getMealNameAttribute()
    {
        if(isset($this->order_id) && $this->order_id != 0){
            $order = Orders::find($this->order_id);  
            return $order == null ? '' :$order->meal_name;  
        }else{
            return "";
        }
    }
    public function getReportShowAttribute()
    {
        if(isset($this->status) && $this->status == 1){
            $add_one_day =  Carbon::parse($this->date)->addDay();
            // if(Carbon::now()->format('Y-m-d') <= $add_one_day->format('Y-m-d')){
                return 1;
            // }else{ 
            //     return 0;
            // }
        }else{
            return 0;
        }
    }
    public function getCancelEditShowAttribute()
    {
        $order_replacement_before_hour = CommonHelper::ConfigGet('order_replacement_before_hour');
        $newDateTime = Carbon::parse($this->date)->subHours($order_replacement_before_hour);
        if(Carbon::now()->format('Y-m-d') <= $newDateTime->format('Y-m-d')){
            return 1;
        }else{ 
            return 0;
        }
    }

    public function order_products() {
         return $this->hasMany('App\Models\OrdersProduct', 'date_id', 'id');
        //return $this->hasMany('App\Models\OrdersProduct', 'date_id');
	}

    public function orders() {
         return $this->hasOne('App\Models\Orders', 'id', 'order_id');
        //return $this->hasMany('App\Models\OrdersProduct', 'date_id');
    }
}
