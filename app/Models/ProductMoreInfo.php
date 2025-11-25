<?php

namespace App\Models;


use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class ProductMoreInfo extends Model
{
    
    protected $table = 'product_more_info';

    protected $fillable = [
        'product_id',
        'country_origin',
        'manufacturer',
        'importer',
        'packer',
        'delete_status'
    ];

    protected $casts = [
        'product_id' => 'integer',
        'delete_status' => 'integer'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
