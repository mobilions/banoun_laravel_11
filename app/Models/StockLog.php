<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    protected $fillable = [
        'stock_id',
        'product_id',
        'variant_id',
        'previous_quantity',
        'process_quantity',
        'total_quantity',
        'process',
        'action',
        'cart_id',
        'performed_by'
    ];

    protected $casts = [
        'stock_id' => 'integer',
        'product_id' => 'integer',
        'variant_id' => 'integer',
        'previous_quantity' => 'integer',
        'process_quantity' => 'integer',
        'total_quantity' => 'integer',
        'cart_id' => 'integer',
        'performed_by' => 'integer',
    ];

    // Relationships
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(Productvariant::class, 'variant_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'performed_by');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}