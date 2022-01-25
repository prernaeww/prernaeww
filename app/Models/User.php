<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Carbon\Carbon;
use CommonHelper;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'phone',
        'gender',
        'dob',
        'department',
        'school',
        'grade',
        'class',
        'address',
        'zipcode',
        'latitude',
        'longitude',
        'start_time',
        'end_time',
        'parent_id',
        'delivery_type',
        'status',
        'profile_picture',        
        'remember_token',        
        'social_type',
        'social_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hasRole() {
        $groups = DB::table('users_group')->whereUserId($this->id)->first();
        return !empty($groups) ? $groups->group_id : false;
    }
    public function isActive() {
        if($this->status == 1){
            return true;
        }else{
            return false;
        }
    }

    public function isBoardActive() {
        $status = DB::table('users')->whereId($this->parent_id)->value('status');        
        return $status == 1 ? true : false;
    }

    public function BoardName() {
        $board = DB::table('users')->whereId($this->parent_id)->first();
        if(isset($board) && !empty($board)){            
            return $board->first_name.' '.$board->last_name;
        }else{
            return "";
        }
    }
    
    public function getProfilePictureAttribute($profile_picture) {
        return $profile_picture == null ? url('/images/default.png') : url('/images/users/'.$profile_picture);
    }
    public function children() {
        return $this->hasMany('App\Models\User', 'parent_id', 'id');
    }
    public function school() {
        return $this->hasOne('App\Models\School', 'id', 'school');
    }
    public function category() {
        return $this->hasMany('App\Models\Category', 'canteen_id', 'id');
    }
    public function product() {
        return $this->hasMany('App\Models\Product', 'canteen_id', 'id');
    }

    public function board() {   
        return $this->hasMany('App\Models\User', 'parent_id', 'id');    
    }

    public function canteen_school() {
        return $this->hasMany('App\Models\School', 'canteen_id', 'id');
    }
    public function devices() {
        return $this->hasOne('App\Models\Devices', 'user_id', 'id');
    }

    /**
     *  revokes all login tokens
     */
    public function revokeAllTokens()
    {

        foreach ($this->tokens as $token) {
                $token->revoke();
        }
    }

    protected $appends = array('has_children','group','school_name','parent_name','age','phone_formatted','grade_name','grade_slug');

    public function getHasChildrenAttribute()
    {
        $user = User::where('parent_id',$this->id)->where('status',1)->get(); 
        if(count($user) >= 0){
            return count($user);
        }
        return 0;
    }
    public function getSchoolNameAttribute()
    {
        $school = School::where('id',$this->school)->first(); 
        if(isset($school)){
            return $school['name'];
        }
        return '';
    }
    public function getGradeNameAttribute()
    {
        $grade = Grade::where('id',$this->grade)->first(); 
        if(isset($grade)){
            return $grade['name'];
        }
        return '';
    }
    public function getGradeSlugAttribute()
    {
        $grade = Grade::where('id',$this->grade)->first(); 
        if(isset($grade)){
            return $grade['slug'];
        }
        return '';
    }
    
    public function getPhoneFormattedAttribute()
    {
        if (isset($this->phone) && $this->phone != "") {
            $phone = CommonHelper::SetPhoneFormat($this->phone);
            return $phone;
        }else{
            return '';
        }
    }
    public function getGroupAttribute()
    {
        $user = UsersGroup::where('user_id',$this->id)->first(); 
        return $user->group_id;
    }
    public function getParentNameAttribute()
    {
        if($this->parent_id != 0){
            $user = User::where('id',$this->parent_id)->first(); 
            return $user->first_name.' '.$user->last_name;
        }else{
            return "";
        }
    }
    public function getAgeAttribute()
    {
        if($this->dob != null){
            $years = Carbon::parse($this->dob)->age;
            return $years;
        }else{
            return "";
        }
    }

    public function findForPassport($username)
    {
        return $this->where('id', $username)->first();
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Orders', 'user_id', 'id');
    }

    public function scopeIsWithinGetDistance($query, $coordinates)
    {

        $haversine = "(111.13384 * acos(cos(radians(" . $coordinates['latitude'] . ")) 
                        * cos(radians(`latitude`)) 
                        * cos(radians(`longitude`) 
                        - radians(" . $coordinates['longitude'] . ")) 
                        + sin(radians(" . $coordinates['latitude'] . ")) 
                        * sin(radians(`latitude`))))";

        return $query->select('*')
                    ->selectRaw("{$haversine} AS distance")
                    ->orderBy('distance', 'asc');
    }
}
