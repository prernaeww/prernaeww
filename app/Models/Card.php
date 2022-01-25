<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'payment_cards';
    protected $fillable = [
         'user_id',
         'card_number',
         'expiry_month_year',
         'type',
         'token'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = array('image', 'card_type');

    public function getImageAttribute() {
        return url('/images/cards/'.$this->type.'.png');
    }

    public function getCardTypeAttribute() {
        $card_types = config('app.card_type');
        return $card_types[$this->type];
    }

}
