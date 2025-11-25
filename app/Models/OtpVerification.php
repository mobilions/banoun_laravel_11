<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    
    protected $fillable = [
        'field',
        'value',
        'otp',
        'created_by',
        'updated_by',
        'verify_status'
    ];

    protected $casts = [
        'otp' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'verify_status' => 'integer'
    ];

}
