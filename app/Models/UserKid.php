<?php

namespace App\Models;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserKid extends Model
{
    
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'dob',
        'imgfile',
        'created_by',
        'updated_by',
        'delete_status'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'dob' => 'date',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'delete_status' => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
