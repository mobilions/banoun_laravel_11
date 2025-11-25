<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerUrl extends Model
{
    
    protected $table = 'banner_url';

    protected $fillable = [
        'name',
        'base_id',
        'base_url'
    ];

    // Relationships
    public function base()
    {
        return $this->belongsTo(Base::class);
    }
}
