<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SoftDeleteByStatus;

class Searchtag1 extends Model
{
    use SoftDeleteByStatus;

    protected $table = 'search_tags';

    protected $fillable = [
        'title',
        'title_ar',
        'count',
        'created_by',
        'updated_by',
        'delete_status'
    ];

    protected $casts = [
        'count' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'delete_status' => 'integer'
    ];
}
