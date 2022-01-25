<?php

namespace App\Models;

use App\Notifications\customPasswordResetNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;

class Store extends Authenticatable {

    use HasFactory, Notifiable, SoftDeletes;

    /**

     * The attributes that are mass assignable.

     *

     * @var array

     */

    protected $table = 'users';

    protected $fillable = [

        'name', 'email', 'password',

    ];

    /**

     * The attributes that should be hidden for arrays.

     *

     * @var array

     */

    protected $hidden = [

        'password', 'remember_token',

    ];

    protected $appends = [
        // 'is_favorite',
    ];

    /**

     *  over ride the default password notification

     */

    public function sendPasswordResetNotification($token) {

        $userType = request()->get('user_type');

        $email = request()->get('email');

        $this->notify(new customPasswordResetNotification($token, $userType, $email));
    }

    /**

     *  deletes the profile picture of the user

     */

    public function deleteProfilePicture() {

        $this->getFirstMedia('admin/profile-picture') ? $this->getFirstMedia('admin/profile-picture')->delete() : null;
    }

    /**

     * returns the user default profile picture if found

     */

    public function getProfilePictureAttribute() {

        return $this->getFirstMedia('admin/profile-picture') ? $this->getFirstMedia('admin/profile-picture')->getFullUrl() : asset('default_images/admin.png');
    }

    public function scopeIsWithinGetDistance($query, $coordinates) {

        $haversine = "(111.13384 * acos(cos(radians(" . $coordinates['latitude'] . "))

                        * cos(radians(`latitude`))

                        * cos(radians(`longitude`)

                        - radians(" . $coordinates['longitude'] . "))

                        + sin(radians(" . $coordinates['latitude'] . "))

                        * sin(radians(`latitude`))))";

        return $query->select('*')

            ->when($coordinates['longitude'] && $coordinates['latitude'], function ($query) use ($haversine) {

                $query->selectRaw("{$haversine} AS distance");
            })

            ->when(($coordinates['longitude'] == '' || $coordinates['latitude'] == '') || ($coordinates['longitude'] == '0' || $coordinates['latitude'] == '0'), function ($query) {

                $query->selectRaw("NULL AS distance");
            })

            ->orderBy('distance', 'asc');

        // $haversine = "(111.13384 * acos(cos(radians(" . $coordinates['latitude'] . "))

        //                 * cos(radians(`latitude`))

        //                 * cos(radians(`longitude`)

        //                 - radians(" . $coordinates['longitude'] . "))

        //                 + sin(radians(" . $coordinates['latitude'] . "))

        //                 * sin(radians(`latitude`))))";

        // return $query->select('*')

        //     ->selectRaw("{$haversine} AS distance")

        //     ->orderBy('distance', 'asc');

    }

    public function product() {
        return $this->hasMany('App\Models\Product', 'store_id', 'id');
    }

    public function getIsFavoriteAttribute() {
        // $user_id = isset(request()->user()) ? request()->user()->id : '';
        $user_id = '';
        if (request()->user()) {
            $user_id = request()->user()->id;
        }
        // $user_id = '25';
        $is_fav = FavoriteStore::whereUserId($user_id)->whereStoreId($this->id)->first();
        return isset($is_fav) ? true : false;
    }

    public function scopeGroupAllProvider($query, $provider_grp = '') {
        return $query->select('users.*')->Join('users_groups as ug', function ($q) use ($provider_grp) {
            $q->on('users.id', '=', 'ug.user_id');
            $q->where('ug.group_id', '=', $provider_grp);
        });
    }
}
