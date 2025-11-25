<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SizeChart extends Model
{
    
    protected $fillable = [
        'name',
        'type',
        'description',
        'description_ar',
        'delete_status'
    ];

    protected $casts = [
        'delete_status' => 'integer'
    ];

}
