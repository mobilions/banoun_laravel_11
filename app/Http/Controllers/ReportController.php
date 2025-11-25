<?php
namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\Cartmaster;
use App\Models\Orderlog;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Productvariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Auth;
use Mail;
class ReportController extends Controller
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
    public function order(Request $request)
    {
        $title = "Orders Report";

        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));

        $indexes = Cartmaster::whereBetween('created_at',[$fromdate, $todate1])->get();

        return view('report.order',compact('title','indexes','fromdate','todate'));  
    }

    public function stock()
    {
        $title = "Stocks Report";
        $indexes = Productvariant::join('products', 'productvariants.product_id', '=', 'products.id')->addSelect('products.name as product','products.name_ar as products_ar','products.category_id','products.subcategory_id','products.brand_id','productvariants.*')->get();
        return view('report.stock',compact('title','indexes'));
    }



    
}
