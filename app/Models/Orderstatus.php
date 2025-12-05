<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orderstatus extends Model
{
    protected $table = 'orderstatus';

    protected $fillable = [
        'name',
        'name_ar',
        'color'
    ];

    public static function FindName($id)
    {
        $status = self::where('id', $id)->first();
        return $status ? $status->name : '';
    }

    public static function findUserVal($id, $field = 'name')
    {
        $status = self::where('id', $id)->first();
        if (!$status) {
            return '';
        }
        return $status->$field ?? '';
    }
}
