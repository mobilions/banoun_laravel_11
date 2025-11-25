<?php

namespace App\Models;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SearchTerm extends Model
{
    
    protected $fillable = [
        'user_id',
        'keyword',
        'key_id',
        'delete_status'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'key_id' => 'integer',
        'delete_status' => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function key()
    {
        return $this->belongsTo(Key::class);
    }
}
