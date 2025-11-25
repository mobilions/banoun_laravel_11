<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    
    protected $fillable = [
        'zone_id',
        'name',
        'name_ar',
        'description',
        'delivery_charge',
        'link_id',
        'delete_status'
    ];

    protected $casts = [
        'zone_id' => 'integer',
        'delivery_charge' => 'decimal:2',
        'link_id' => 'integer',
        'delete_status' => 'integer'
    ];

    // Relationships
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
    public function link()
    {
        return $this->belongsTo(Link::class);
    }
}
