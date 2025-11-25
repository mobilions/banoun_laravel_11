<?php

namespace App\Models;


use App\Models\Product;
use App\Models\Variant;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    
    protected $fillable = [
        'product_id',
        'variant_id',
        'imageurl',
        'created_by',
        'updated_by',
        'delete_status'
    ];

    protected $casts = [
        'product_id' => 'integer',
        'variant_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
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
}
