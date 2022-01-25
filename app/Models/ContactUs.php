<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;

    protected $table = 'contact_us';

    public function user_name(){
        return $this->belongsTo(User::class, 'user_id', 'id'); 
    }

    public function getDocumentAttribute($document) {
        return $document == null ? '' : 'images/contact_us/'.$document;
    }


}
