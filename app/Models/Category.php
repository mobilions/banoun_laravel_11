<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SoftDeleteByStatus;

class Category extends Model
{
    use SoftDeleteByStatus;
    
    protected $fillable = [
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

    public static function FindName($id){

        $product = Category::select('name','name_ar')->where('id',$id)->first();

        $name =  '';

        if($product){ $name =  $product->name; }

		return "$name";

	}

    public function getImageurlAttribute($value){
        return $value != "" ? asset($value) : "";
    }
}
