<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    
    protected $fillable = [
        'coupon_type',
        'coupon_type_id',
        'price_type',
        'coupon_val',
        'coupon_code',
        'coupon_code_ar',
        'created_by',
        'updated_by',
        'delete_status'
    ];

    protected $casts = [
        'coupon_type_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'delete_status' => 'integer'
    ];

    // Relationships
    public function couponType()
    {
        return $this->belongsTo(CouponType::class);
    }
}
