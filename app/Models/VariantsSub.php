<?php

namespace App\Models;


use App\Models\Variant;
use Illuminate\Database\Eloquent\Model;

class VariantsSub extends Model
{
    
    protected $table = 'variants_sub';

    protected $fillable = [
        'variant_id',
        'name',
        'name_ar',
        'color_val',
        'created_by',
        'updated_by',
        'delete_status'
    ];

    protected $casts = [
        'variant_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'delete_status' => 'integer'
    ];

    
    public function getNameAttribute($value){
        return $value != null ? $value : "";
    }
    
    public function getColorValAttribute($value){
        return $value != null ? $value : "";
    }

    // Relationships
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
}
