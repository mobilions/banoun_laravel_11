<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emailtemplate extends Model
{
    
    protected $fillable = [
        'mail_type',
        'lable',
        'name',
        'message',
        'message_ar',
        'created_by',
        'updated_by',
        'delete_status'
    ];

    protected $casts = [
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'delete_status' => 'integer'
    ];

}
