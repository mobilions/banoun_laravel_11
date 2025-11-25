<?php

namespace App\Models;


use App\Models\Product;
use App\Models\Variant;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    
    protected $fillable = [
        'product_id',
        'variant_id',
        'size_id',
        'qty',
        'created_by',
        'updated_by',
        'status',
        'delete_status'
    ];

    protected $casts = [
        'product_id' => 'integer',
        'variant_id' => 'integer',
        'size_id' => 'integer',
        'qty' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'status' => 'integer',
        'delete_status' => 'integer'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}
