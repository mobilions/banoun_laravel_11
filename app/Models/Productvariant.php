<?php

namespace App\Models;


use App\Models\Product;
use App\Models\Variantsub;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SoftDeleteByStatus;

class Productvariant extends Model
{
    use SoftDeleteByStatus;
    
    protected $fillable = [
        'product_id',
        'size_id',
        'color_id',
        'price',
        'available_quantity',
        'imageurl',
        'imageurl2',
        'imageurl3',
        'created_by',
        'updated_by',
        'delete_status'
    ];

    protected $casts = [
        'size_id' => 'integer',
        'color_id' => 'integer',
        'price' => 'decimal:2',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'delete_status' => 'integer'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function size()
    {
        return $this->belongsTo(Size::class);
    }
    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public static function FindName($id){

        $product = Productvariant::select('size_id','color_id')->where('id',$id)->first();

        $name =  'Default';

        $v1='';

        $v2='';

        if($product){ 



        		if($product->size_id!=''){

        			$v1 = Variantsub::FindName($product->size_id);

        			$name=$v1;

        		}

        		if($product->color_id!=''){

        			$v2 = Variantsub::FindName($product->color_id);

        			$name=$name.' - '.$v2;

        		}

        	

        }

		return $name;

	}
}
