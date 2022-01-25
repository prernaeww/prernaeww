<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class FavoriteStore extends Model

{

    use HasFactory;



     protected $casts = [        

        'created_at' => 'datetime',

        'updated_at' => 'datetime',

    ];



    protected $table = 'favorite_stores';

   public function store()
   {
      return $this->hasMany('App\Models\Store', 'id', 'store_id');
   }

}

