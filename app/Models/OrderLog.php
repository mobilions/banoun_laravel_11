<?php

namespace App\Models;


use App\Models\Cartmaster;
use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    
    protected $table = 'order_log';

    protected $fillable = [
        'cartmaster_id',
        'status_id',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'cartmaster_id' => 'integer',
        'status_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    // Relationships
    public function cartmaster()
    {
        return $this->belongsTo(Cartmaster::class);
    }
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
