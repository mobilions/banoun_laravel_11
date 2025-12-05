<?php

namespace App\Models;


use App\Models\Product;
use App\Models\User;
use App\Models\Variant;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    
    protected $fillable = [
        'product_id',
        'user_id',
        'variant_id',
        'size_id',
        'qty',
        'actual_price',
        'offer_price',
        'total_price',
        'master_id',
        'carted',
        'from_wishlist',
        'created_by',
        'updated_by',
        'delete_status'
    ];

    protected $casts = [
        'product_id' => 'integer',
        'user_id' => 'integer',
        'variant_id' => 'integer',
        'size_id' => 'integer',
        'qty' => 'integer',
        'actual_price' => 'decimal:2',
        'offer_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'master_id' => 'integer',
        'carted' => 'integer',
        'from_wishlist' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'delete_status' => 'integer'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function variant()
    {
        return $this->belongsTo(Productvariant::class, 'variant_id');
    }
    public function size()
    {
        return $this->belongsTo(Size::class);
    }
    public function master()
    {
        return $this->belongsTo(Cartmaster::class, 'master_id');
    }
}
