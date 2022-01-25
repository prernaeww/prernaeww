<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Category extends Model
{

    use HasFactory,SoftDeletes;
    protected $table = 'category';
    protected $fillable = [
        'name',
        'image',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


  

    public function getImageAttribute($image) {
		return $image == null ? url('/images/default.png') : url('/images/category/'.$image);
	}

    
}
