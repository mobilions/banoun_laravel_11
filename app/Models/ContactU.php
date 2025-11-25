<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactU extends Model
{
    
    protected $fillable = [
        'name',
        'email',
        'message',
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
