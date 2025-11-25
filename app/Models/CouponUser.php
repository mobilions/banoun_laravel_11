<?php

namespace App\Models;


use App\Models\Coupon;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CouponUser extends Model
{
    
    protected $fillable = [
        'user_id',
        'coupon_id',
        'is_active',
        'sub_price',
        'discount_price',
        'total_price',
        'token',
        'coupon_code',
        'promo_price',
        'is_giftwrap',
        'giftwrap_msg',
        'address_id',
        'paymenttype',
        'use_credit'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'coupon_id' => 'integer',
        'is_active' => 'integer',
        'sub_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'promo_price' => 'decimal:2',
        'is_giftwrap' => 'integer',
        'address_id' => 'integer',
        'use_credit' => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
