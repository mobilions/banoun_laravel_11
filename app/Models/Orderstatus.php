<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orderstatus extends Model
{
    
    protected $table = 'orderstatus';

    protected $fillable = [
        'name',
        'color'
    ];

}
