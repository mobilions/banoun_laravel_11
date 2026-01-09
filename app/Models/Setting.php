<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    
    protected $fillable = [
        'company',
        'company_ar',
        'contact_person',
        'phone',
        'email',
        'support_phone',
        'support_email',
        'location',
        'description',
        'description_ar',
        'header',
        'header_ar',
        'imageurl',
        'facebook',
        'twitter',
        'instagram',
        'whatsapp',
        'google',
        'giftwrap_price',
        'created_by',
        'updated_by',
        'delete_status',
        'mail_configurtion', //json field 1000 varchar length
    ];

    protected $casts = [
        'giftwrap_price' => 'decimal:2',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'delete_status' => 'integer',
        'mail_configurtion' => 'array'
    ];

    

}
