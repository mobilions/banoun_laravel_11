<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SoftDeleteByStatus;

class Variant extends Model
{
    use SoftDeleteByStatus;
    
    protected $fillable = [
        'name',
        'name_ar',
        'description',
        'description_ar',
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
