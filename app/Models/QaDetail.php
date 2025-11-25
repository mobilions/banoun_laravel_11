<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QaDetail extends Model
{
    
    protected $fillable = [
        'title',
        'content',
        'title_ar',
        'content_ar',
        'type',
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
