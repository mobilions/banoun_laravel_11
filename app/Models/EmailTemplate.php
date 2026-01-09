<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    
    protected $fillable = [
        'mail_type',
        'lable',
        'name',
        'message',
        'message_ar',
        'created_by',
        'updated_by',
        'delete_status'
    ];

    protected $casts = [
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'delete_status' => 'integer'
    ];

    public static function byType($type)
    {
        return self::where('mail_type', $type)
            ->where('delete_status', 0)
            ->first();
    }

}
