<?php



namespace App\Http\Controllers;



use App\Models\Stock;

use App\Models\Product;

use App\Models\Productvariant;
use App\Enums\StockProcess;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use Auth;



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



    public function index($variant_id,$product_id)

    {

        $title = "Stock";

        $indexes = Stock::where('variant_id',$variant_id)->get();

        $addstock = Stock::where('variant_id',$variant_id)->where('process',StockProcess::ADD)->get();

        return view('stock.index',compact('title','indexes','variant_id','product_id','addstock'));  

    }



    public function store(Request $request)

    {
        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'required|exists:productvariants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        Stock::updateStock($request->product_id,$request->variant_id,$request->quantity,StockProcess::ADD);



        return redirect('stock/'.$request->variant_id.'/'.$request->product_id);

    }



    public function update(Request $request)

    {
        $this->validate($request, [
            'editid' => 'required|exists:stocks,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $data = Stock::find($request->editid);

        if (empty($data)) { 
            return redirect()->back()->with('error', 'Stock record not found.');
        }

        $data->quantity = $request->quantity;

        $data->updated_by=Auth::user()->id;

        $data->save();



        return redirect()->back();

    }



    public function stcokapprove($id)

    {

        $data = Stock::find($id);

        if (empty($data)) { 
            return redirect()->back()->with('error', 'Stock record not found.');
        }

        $data->status = 1;

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

        $data->save();

        $st = Stock::where('id',$id)->first();

        return redirect('stocklist');

    }



    public function stocklist(){

        $title = "Stock List";

        $indexes = Stock::where('process',StockProcess::ADD)->where('status',0)->get();

        return view('stock.stocklist',compact('title','indexes'));

    }



    public function stocklog()

    {

        $title = "Stock List";

        $indexes = Productvariant::join('products', 'productvariants.product_id', '=', 'products.id')->addSelect('products.name as product','products.name_ar as products_ar','products.category_id','products.subcategory_id','products.brand_id','productvariants.*')->get();

        return view('stock.stocklog',compact('title','indexes'));

    }



    

}

