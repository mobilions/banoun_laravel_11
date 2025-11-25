<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    
    protected $fillable = [
        'imageurl',
        'image_sm',
        'order_id',
        'created_by',
        'updated_by',
        'delete_status'
    ];

    protected $casts = [
        'order_id' => 'decimal:2',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'delete_status' => 'integer'
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
