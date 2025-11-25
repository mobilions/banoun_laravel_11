<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductInfoMaster extends Model
{
    
    protected $table = 'product_info_master';

    protected $fillable = [
        'name'
    ];

}
