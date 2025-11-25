<?php

namespace App\Models;


use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class ProductInfo extends Model
{
    
    protected $table = 'product_info';

    protected $fillable = [
        'product_id',
        'master_id',
        'detail',
        'delete_status'
    ];

    protected $casts = [
        'product_id' => 'integer',
        'master_id' => 'integer',
        'delete_status' => 'integer'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function master()
    {
        return $this->belongsTo(Master::class);
    }
}
