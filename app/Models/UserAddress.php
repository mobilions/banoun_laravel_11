<?php

namespace App\Models;


use App\Models\Area;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    
    protected $fillable = [
        'user_id',
        'mobile',
        'country_mobile',
        'landline',
        'country_landline',
        'name',
        'area_id',
        'area',
        'type',
        'block',
        'street',
        'avenue',
        'building',
        'floor',
        'apartment',
        'additional_info',
        'latitude',
        'longitude',
        'is_default',
        'created_by',
        'updated_by',
        'delete_status'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'area_id' => 'integer',
        'is_default' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'delete_status' => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
