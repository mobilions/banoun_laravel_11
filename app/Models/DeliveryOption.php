<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOption extends Model
{
    
    protected $fillable = [
        'name',
        'name_ar',
        'imageurl',
        'icon',
        'created_by',
        'updated_by',
        'delete_status'
    ];

    protected $casts = [
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'delete_status' => 'integer'
    ];

    public function getImageurlAttribute($value){
        return $value != "" ? asset($value) : "";
    }
}
