<?php

namespace App\Models;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Productvariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Enums\StockProcess;
use App\Models\StockLog;

class Stock extends Model
{
    
    protected $fillable = [
        'product_id',
        'variant_id',
        'quantity',
        'status',
        'cart_id',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'product_id' => 'integer',
        'variant_id' => 'integer',
        'quantity' => 'integer',
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

    public static function updateStock($product_id, $productvariants_id, $quantity, $process, $cart_id = 0)
    {
        DB::transaction(function () use ($product_id, $productvariants_id, $quantity, $process, $cart_id) {
            $variant = Productvariant::where('id', $productvariants_id)->lockForUpdate()->first();
            
            if (empty($variant)) { 
                throw new \Exception('Product variant not found.');
            }

            $previous_qty = $variant->available_quantity ?? 0;
            
            // Create stock entry
            $data = new Stock; 
            $data->product_id = $product_id;
            $data->variant_id = $productvariants_id;
            $data->quantity = $quantity;
            $data->cart_id = $cart_id;
            $data->created_by = Auth::user()->id;
            $data->updated_by = Auth::user()->id;
            $data->save();

            // Calculate new total quantity
            $total_qty = self::stockVariant($productvariants_id);

            // Update variant quantity
            $variant->available_quantity = $total_qty;
            $variant->updated_by = Auth::user()->id;
            $variant->save();

            // Create stock log
            StockLog::create([
                'stock_id' => $data->id,
                'product_id' => $product_id,
                'variant_id' => $productvariants_id,
                'previous_quantity' => $previous_qty,
                'process_quantity' => $quantity,
                'total_quantity' => $total_qty,
                'process' => $process,
                'action' => 'CREATED',
                'cart_id' => $cart_id,
                'performed_by' => Auth::user()->id
            ]);
        });
    }

   public  static function stockApproval($product_id)
   {
  		$data = Stock::where('product_id',$product_id)->update(['status' => 1]); 
   }

   public static function stockVariant($variant_id) {
    return Stock::where('variant_id', $variant_id)->sum('quantity');
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
