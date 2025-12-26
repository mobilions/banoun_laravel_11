<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Usbanner extends Model
{
    
    protected $fillable = [
        'shopby',
        'category_id',
        'redirect_type',
        'redirect_by',
        'name',
        'name_ar',
        'description',
        'description_ar',
        'imageurl',
        'grid',
        'type',
        'categoryId',
        'image_sm',
        'created_by',
        'updated_by',
        'delete_status'
    ];

    protected $casts = [
        'grid' => 'integer',
        'categoryId' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'delete_status' => 'integer'
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    /**
     * Helper to fetch a single column value from any table.
     *
     * @param  string  $table
     * @param  string  $whereColumn
     * @param  mixed   $value
     * @param  string  $targetColumn
     * @return mixed
     */
    public static function getColumnWhere(string $table, string $whereColumn, $value, string $targetColumn)
    {
        if (blank($table) || blank($whereColumn) || blank($targetColumn)) {
            return null;
        }

        return DB::table($table)
            ->where($whereColumn, $value)
            ->value($targetColumn);
    }
    
    public function getImageurlAttribute($value){
        return $value != "" ? asset($value) : "";
    }
}
