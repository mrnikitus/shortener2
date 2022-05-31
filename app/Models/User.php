<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

//    public function addresses_count() {
//        return $this->hasMany(Address::class,'user_id')->count();
//    }

//    /**
//     * @var string[]
//     */
//    protected $appends = ['addresses_count'];
//
//    public function getAddressesCountAttribute() {
//        return $this->hasMany(Address::class,'user_id')->count();
//    }


    public function addresses() {
        return $this->hasMany(Address::class, 'user_id');
    }

    public function current_count() {
        return $this->hasMany(Address::class, 'user_id')->count();
//        return Address::where('user_id',$this->id)->count();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
