<?php

namespace App\Models;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserChange extends Model
{
    
    protected $fillable = [
        'user_id',
        'field',
        'value',
        'is_verified'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'is_verified' => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
