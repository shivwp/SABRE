<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Laravel\Cashier\Billable;

class User extends Authenticatable

{

    use HasFactory, Notifiable,HasApiTokens,Billable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'dob',
        'gender',
        'phone',
        'wallet',
        'user_status',
        'profile_image',
        'city',
        'state',
        'discription',
        'password',
        'address',
        'zip',
        'is_otp_verified',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    public function Models()
    {
        return $this->hasOne('App\Models\Models');
    }

    public function usermeta()
    {
        return $this->hasMany('App\Models\UserMeta');
    }

    public function jobs()
    {
        return $this->belongsToMany('App\Models\Jobs');
    }
    public function tasklog()
    {
        return $this->belongsToMany('App\Models\TaskLog');
    }

    public function withdrow()
    {
        return $this->belongsToMany(Withdrow::class,'vendor_id');

    }
    public function coupn()
    {
        return $this->belongsToMany(Coupon::class);
    }
    public function order(){

        return $this->hasMany(Order::class); 

    }
    public function cities()
    {
        return $this->belongsToMany(City::class);
    }
}



