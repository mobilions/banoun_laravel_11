<?php

namespace App\Models;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\UserAddress;
use App\Models\UserCredit;
use App\Models\UserKid;
use App\Models\Wishlist;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Traits\SoftDeleteByStatus;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeleteByStatus;

    protected $guarded = ['id'
        // 'role',
        // 'is_verified',
        // 'delete_status',
        // 'created_by',
        // 'updated_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
    ];

    // Relationships
    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function credits()
    {
        return $this->hasMany(UserCredit::class);
    }

    public function kids()
    {
        return $this->hasMany(UserKid::class);
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_users');
    }
    
    public static function findUserVal($id, $field = 'name')
    {
        $status = self::where('id', $id)->first();
        if (!$status) {
            return '';
        }
        return $status->$field ?? '';
    }
    
    public function getImgfileAttribute($value){
        return $value != "" ? asset($value) : "";
    }
}
