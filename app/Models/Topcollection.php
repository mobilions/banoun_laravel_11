<?php

namespace App\Models;


use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Topcollection extends Model
{
    
    protected $fillable = [
        'category_id',
        'shopby',
        'redirect_type',
        'redirect_by',
        'name',
        'name_ar',
        'description',
        'description_ar',
        'imageurl',
        'type',
        'categoryId',
        'image_sm',
        'grid',
        'created_by',
        'updated_by',
        'delete_status'
    ];

    protected $casts = [
        'categoryId' => 'integer',
        'grid' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'delete_status' => 'integer'
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function getImageurlAttribute($value){
        return $value != "" ? asset($value) : "";
    }
    
    public function getTypeAttribute($value){
        return $value != null ? $value : "";
    }
    
    public function getShopbyAttribute($value){
        return $value != null ? $value : "";
    }
    
    public function getUrlAttribute($value){
        return $value != null ? $value : "";
    }
}
