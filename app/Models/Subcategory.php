<?php

namespace App\Models;


use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SoftDeleteByStatus;

class Subcategory extends Model
{
    use SoftDeleteByStatus;
    
    protected $fillable = [
        'category_id',
        'name',
        'name_ar',
        'description',
        'description_ar',
        'imageurl',
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

    public static function FindName($id){

        $log = Subcategory::select('name','name_ar')->where('id',$id)->first();

        $name =  '';

        if($log){ $name =  $log->name; }

        return "$name";

    }
}
