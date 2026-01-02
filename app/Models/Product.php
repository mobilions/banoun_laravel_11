<?php

namespace App\Models;


use App\Models\Brand;
use App\Models\Category;
use App\Models\Searchtag;
use App\Models\Subcategory;
use App\Models\Productvariant;
use App\Models\ProductImage;
use App\Models\Cart;
use App\Models\Stock;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SoftDeleteByStatus;

class Product extends Model
{
    use SoftDeleteByStatus;
    
    protected $fillable = [
        'category_id',
        'subcategory_id',
        'brand_id',
        'name',
        'name_ar',
        'description',
        'description_ar',
        'more_info',
        'more_info_ar',
        'imageurl',
        'imageurl2',
        'imageurl3',
        'price',
        'price_offer',
        'percentage_discount',
        'is_newarrival',
        'is_trending',
        'is_recommended',
        'is_topsearch',
        'searchtag_id',
        'search_count',
        'created_by',
        'updated_by',
        'accept_status',
        'delete_status',
        'min_age',
        'max_age',
        'colors',
        'size'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_offer' => 'decimal:2',
        'percentage_discount' => 'integer',
        'is_newarrival' => 'integer',
        'is_trending' => 'integer',
        'is_recommended' => 'integer',
        'is_topsearch' => 'integer',
        'search_count' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'accept_status' => 'integer',
        'delete_status' => 'integer'
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function searchtag()
    {
        return $this->belongsTo(Searchtag::class);
    }
    
    // Has Many Relationships
    public function variants()
    {
        return $this->hasMany(Productvariant::class);
    }
    
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    
    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
    
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function getColorsArrayAttribute()
    {
        return $this->colors ? explode(',', $this->colors) : [];
    }
    
    public function getSizeArrayAttribute()
    {
        return $this->size ? explode(',', $this->size) : [];
    }

    public static function FindName($id){

        $product = Product::select('name','name_ar')->where('id',$id)->first();

        $name =  '';

        if($product){ $name =  $product->name.' '.$product->name_ar; }

		return "$name";

	}
    
    public function getImageurlAttribute($value){
        return $value != "" ? asset($value) : "";
    }
}
