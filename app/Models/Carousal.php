<?php

namespace App\Models;


use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Carousal extends Model
{
    
    protected $fillable = [
        'shopby',
        'category_id',
        'name',
        'name_ar',
        'description',
        'description_ar',
        'imageurl',
        'image_sm',
        'created_by',
        'updated_by',
        'delete_status'
    ];

    protected $casts = [
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
}
