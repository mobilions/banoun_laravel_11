<?php



namespace App\Http\Controllers;



use App\Models\Stock;

use App\Models\Product;

use App\Models\Productvariant;
use App\Enums\StockProcess;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class StockController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function __construct()

    {

        $this->middleware('auth'); 

    }



    public function index($variant_id, $product_id)
    {
        $title = "Stock";
        $indexes = StockLog::with(['user', 'product', 'variant'])
            ->where('variant_id', $variant_id)
            ->where('product_id', $product_id)
            ->orderBy('id', 'desc')
            ->get();

        $addstock = Stock::where('variant_id', $variant_id)
            ->orderBy('id', 'desc')
            ->get();
        return view('stock.index', compact('title', 'indexes', 'variant_id', 'product_id', 'addstock'));  
    }


    public function store(Request $request)

    {
        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'required|exists:productvariants,id',
            //'quantity' => 'required|integer|min:1',
        ]);

        $variant = Productvariant::where('id', $request->variant_id)
            ->where('product_id', $request->product_id)
            ->first();

        if (!$variant) {
            return redirect()->back()->with('error', 'Variant does not belong to the selected product.');
        }

        Stock::updateStock($request->product_id,$request->variant_id,$request->quantity,StockProcess::ADD);
        return redirect('stock/'.$request->variant_id.'/'.$request->product_id);

    }


 public function update(Request $request)
    {
        $this->validate($request, [
            'editid' => 'required|exists:stocks,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $data = Stock::find($request->editid);

        if (empty($data)) { 
            return redirect()->back()->with('error', 'Stock record not found.');
        }

        if ((int) $data->status === 1) {
            return redirect()->back()->with('error', 'Approved stock entries cannot be modified.');
        }

        DB::transaction(function () use ($data, $request) {
            $variant = Productvariant::lockForUpdate()->find($data->variant_id);
            
            if (!$variant) {
                throw new \Exception('Product variant not found.');
            }

            $previous_qty = $variant->available_quantity ?? 0;
            $oldQuantity = $data->quantity;
            $newQuantity = $request->quantity;
            
            // Calculate the actual change
            $quantityDifference = $newQuantity - $oldQuantity;
            
            // Determine process type
            if ($quantityDifference > 0) {
                $process = StockProcess::ADD;
                $processQuantity = $quantityDifference;
            } elseif ($quantityDifference < 0) {
                $process = StockProcess::REMOVE;
                $processQuantity = $quantityDifference; // This will be negative
            } else {
                $process = StockProcess::UPDATE;
                $processQuantity = 0;
            }

            // Update stock entry
            $data->quantity = $newQuantity;
            $data->updated_by = Auth::user()->id;
            $data->save();

            // Calculate new total quantity
            $total_qty = Stock::stockVariant($data->variant_id);

            // Update variant quantity
            $variant->available_quantity = $total_qty;
            $variant->updated_by = Auth::user()->id;
            $variant->save();

            // Create stock log
            StockLog::create([
                'stock_id' => $data->id,
                'product_id' => $data->product_id,
                'variant_id' => $data->variant_id,
                'previous_quantity' => $previous_qty,
                'process_quantity' => $processQuantity,
                'total_quantity' => $total_qty,
                'process' => $process,
                'action' => 'UPDATED',
                'cart_id' => $data->cart_id,
                'performed_by' => Auth::user()->id
            ]);
        });

        return redirect()->back()->with('success', 'Stock entry updated successfully.');
    }

    public function details($variant_id)
    {
        $logs = StockLog::with(['user', 'product', 'variant'])
            ->where('variant_id', $variant_id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($log) {
                return [
                    'id' => $log->id,
                    'created_at' => $log->created_at,
                    'process' => $log->process,
                    'process_quantity' => $log->process_quantity,
                    'previous_quantity' => $log->previous_quantity,
                    'total_quantity' => $log->total_quantity,
                    'action' => $log->action,
                    'user' => $log->user ? ['name' => $log->user->name] : null,
                ];
            });
        return response()->json($logs);
    }


    public function stcokapprove($id)

    {

        $data = Stock::find($id);

        if (empty($data)) { 
            return redirect()->back()->with('error', 'Stock record not found.');
        }

        $data->status = 1;
        $data->updated_by = Auth::user()->id;

        $data->save();

        $st = Stock::where('id',$id)->first();



        return redirect('stock/'.$st->variant_id.'/'.$st->product_id);

    }



    public function stcokapprovelist($id)

    {

        $data = Stock::find($id);

        if (empty($data)) { 
            return redirect('stocklist')->with('error', 'Stock record not found.');
        }

        $data->status = 1;
        $data->updated_by = Auth::user()->id;

        $data->save();

        $st = Stock::where('id',$id)->first();

        return redirect('stocklist');

    }



    public function stocklist(){

        $title = "Stock List";

        $indexes = Stock::with([
                'product:id,name,category_id,subcategory_id,brand_id',
                'product.category:id,name',
                'product.subcategory:id,name',
                'product.brand:id,name',
                'variant:id,name'
            ])
            //->where('process',StockProcess::ADD)
            ->where('status',0)
            ->orderByDesc('created_at')
            ->get();

        return view('stock.stocklist',compact('title','indexes'));

    }
    // public function stocklog()
    // {
    //     $title = "Stock List";
    //     $indexes = Productvariant::join('products', 'productvariants.product_id', '=', 'products.id')->addSelect('products.name as product','products.name_ar as products_ar','products.category_id','products.subcategory_id','products.brand_id','productvariants.*')->get();       
        
    //     return view('stock.stocklog',compact('title','indexes'));
    // }
    public function stocklog(Request $request)
    {
        $title = "Stock List";

        $this->validate($request, [
            'search' => 'nullable|string|max:255',
            'category_id' => 'nullable|integer|exists:categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'subcategory_id' => 'nullable|integer|exists:subcategories,id',
            'min_quantity' => 'nullable|numeric|min:0',
            'max_quantity' => 'nullable|numeric|min:0',
        ]);

        $query = Productvariant::query()
                    ->with([
                        'product.category', 
                        'product.brand', 
                        'product.subcategory', 
                        'sizeVariant', 
                        'colorVariant'
                    ])
                    ->addSelect([
                        'productvariants.*',
                        'stocks_sum_quantity' => Stock::selectRaw('COALESCE(SUM(quantity), 0)')
                            ->whereColumn('stocks.variant_id', 'productvariants.id')
                            ->whereColumn('stocks.product_id', 'productvariants.product_id')
                    ]);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('name_ar', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Filter by brand
        if ($request->has('brand_id') && $request->brand_id) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('brand_id', $request->brand_id);
            });
        }

        // Filter by subcategory
        if ($request->has('subcategory_id') && $request->subcategory_id) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('subcategory_id', $request->subcategory_id);
            });
        }

        // Filter by quantity range
        if ($request->has('min_quantity') && $request->min_quantity) {
            $query->havingRaw('stocks_sum_quantity >= ?', [$request->min_quantity]);
        }
        if ($request->has('max_quantity') && $request->max_quantity) {
            $query->havingRaw('stocks_sum_quantity <= ?', [$request->max_quantity]);
        }

        $indexes = $query->havingRaw('stocks_sum_quantity > 0')->get();

        // For filters dropdown
        $categories = \App\Models\Category::active()->get();
        $brands = \App\Models\Brand::active()->get();
        $subcategories = \App\Models\Subcategory::active()->get();

        return view('stock.stocklog', compact('title', 'indexes', 'categories', 'brands', 'subcategories'));
    }

    public function getStockDetails($variantId)
    {
        $stocks = Stock::where('variant_id', $variantId)
           ->with('variant.product')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($stocks);
    }

    public function destroy($id)
    {
        $stock = Stock::where('id', $id)->where('status', 0)->first();

        if (!$stock) {
            return redirect()->back()->with('error', 'Only pending stock entries can be removed.');
        }

        DB::transaction(function () use ($stock) {
            $variantId = $stock->variant_id;
            $variant = Productvariant::lockForUpdate()->find($variantId);
            if ($variant) {
                $stock->previous_quantity = $variant->available_quantity ?? 0;
            }

            $stock->updated_by=Auth::user()->id;
            $stock->save();

            if ($variant) {
                $qty = Stock::stockVariant($variantId);
                $stock->quantity = $qty;
                $stock->save();

                $variant->available_quantity = $qty;
                $variant->updated_by = Auth::user()->id;
                $variant->save();
            }
        });

        return redirect()->back()->with('success', 'Stock entry deleted successfully.');
    }

}

