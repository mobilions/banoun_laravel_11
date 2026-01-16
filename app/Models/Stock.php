<?php

namespace App\Models;


use App\Models\Cart;
use App\Models\Product;
use App\Models\Productvariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Enums\StockProcess;

class Stock extends Model
{
    
    protected $fillable = [
        'product_id',
        'variant_id',
        'quantity',
        'previous_quantity',
        'current_quantity',
        'process',
        'status',
        'cart_id',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'product_id' => 'integer',
        'variant_id' => 'integer',
        'quantity' => 'integer',
        'previous_quantity' => 'integer',
        'current_quantity' => 'integer',
        'status' => 'integer',
        'cart_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    protected $appends = [
        'status_text',
        'status_badge_class',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function variant()
    {
        return $this->belongsTo(Productvariant::class, 'variant_id');
    }
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    public  static function updateStock($product_id,$productvariants_id,$quantity,$process,$cart_id=0)

   {

   	

        DB::transaction(function () use ($product_id,$productvariants_id,$quantity,$process,$cart_id) {

            $variant = Productvariant::where('id',$productvariants_id)->lockForUpdate()->first();
            if (empty($variant)) { 
                throw new \Exception('Product variant not found.');
            }

            $previous_qty = $variant->available_quantity ?? 0;

    		  $data = new Stock; 
            $data->product_id = $product_id;
            $data->variant_id = $productvariants_id;
            $data->quantity    = $quantity;
            $data->previous_quantity = $previous_qty;
            $data->cart_id    = $cart_id;
            $data->process    = $process;
            $data->updated_by=Auth::user()->id;
            $data->save();

            $qty=self::stockVariant($productvariants_id);

            $data->current_quantity = $qty;
            $data->save();

            $variant->available_quantity = $qty;
            $variant->updated_by=Auth::user()->id;
            $variant->save();
        });

   }



   public  static function stockApproval($product_id)

   {

  		$data = Stock::where('product_id',$product_id)->update(['status' => 1]); 

   }



   public  static function stockProduct($product_id)

   {

   		$add=Stock::whereIn('process',[StockProcess::ADD, StockProcess::UPDATE])->where('product_id',$product_id)->sum('quantity');

   		$sales=Stock::where('process',StockProcess::SALES)->where('product_id',$product_id)->sum('quantity');

   		$return=Stock::where('process',StockProcess::RETURN)->where('product_id',$product_id)->sum('quantity');

   		$cancel=Stock::where('process',StockProcess::CANCEL)->where('product_id',$product_id)->sum('quantity');

   		$replace=Stock::where('process',StockProcess::REPLACE)->where('product_id',$product_id)->sum('quantity');



   		$stock=$add+$return+$cancel+$replace-$sales;



   		return $stock;



   }



   public  static function stockVariant($variant_id)

   {

   		$add=Stock::whereIn('process',[StockProcess::ADD, StockProcess::UPDATE])->where('variant_id',$variant_id)->sum('quantity');

   		$sales=Stock::where('process',StockProcess::SALES)->where('variant_id',$variant_id)->sum('quantity');

   		$return=Stock::where('process',StockProcess::RETURN)->where('variant_id',$variant_id)->sum('quantity');

   		$cancel=Stock::where('process',StockProcess::CANCEL)->where('variant_id',$variant_id)->sum('quantity');

   		$replace=Stock::where('process',StockProcess::REPLACE)->where('variant_id',$variant_id)->sum('quantity');



   		$stock=$add+$return+$cancel+$replace-$sales;



   		return $stock;



   }



  public static function FindStatus($id){

       $product = Stock::select('status')->where('id',$id)->first();

       if(!$product){
            return '';
       }

       return $product->status_text;

 }

 public function getStatusTextAttribute(): string
 {
    return (int) $this->status === 1 ? 'approved' : 'pending';
 }

 public function getStatusBadgeClassAttribute(): string
 {
    return $this->status_text === 'approved'
        ? 'badge badge-soft-success font-size-12'
        : 'badge badge-soft-danger font-size-12';
 }
}
