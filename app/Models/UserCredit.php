<?php

namespace App\Models;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserCredit extends Model
{
    
    protected $fillable = [
        'user_id',
        'balance',
        'delete_status'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'balance' => 'decimal:2',
        'delete_status' => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
