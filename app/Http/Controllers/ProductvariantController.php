<?php



namespace App\Http\Controllers;



use App\Models\Productvariant;

use App\Models\Variant;

use App\Models\Variantsub;

use App\Models\Cart;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use Auth;



class ProductvariantController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index($id)

    {   

        $title = "Product Variant";

        $indexes = Productvariant::active()->where('product_id',$id)->get();

       

        return view('productvariant.index',compact('title','indexes','id'));

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create($id)

    {   

        $title = "Product Variant";

        $size = Variantsub::active()->where('variant_id','1')->get();

        $color = Variantsub::active()->where('variant_id','2')->get();

        return view('productvariant.create',compact('title','id','size','color'));

    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)

    {
        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
            'color_id' => 'nullable|exists:variants_sub,id',
            'size_id' => 'nullable|exists:variants_sub,id',
            'price' => 'required|numeric|min:0',
            'imgfile' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'imgfile2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'imgfile3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);



        $imgurl    = '';

        $path   = $request->file('imgfile');

        if (!empty($path)) {

        $store  = Storage::putFile('public/image', $path);

            //$imgurl    = Storage::url($store);

            //$imgurl = url('/').'/storage/app/'.$store;

            $imgurl = config('app.imgurl').basename($store);

        }

        //echo "hi"; exit();



        $imgurl2    = '';

        $path   = $request->file('imgfile2');

        if (!empty($path)) {

            $store  = Storage::putFile('public/image', $path);

            //$imgurl    = Storage::url($store);

            //$imgurl = url('/').'/storage/app/'.$store;

            $imgurl2 = config('app.imgurl').basename($store);

        }



        $imgurl3    = '';

        $path   = $request->file('imgfile3');

        if (!empty($path)) {

            $store  = Storage::putFile('public/image', $path);

            //$imgurl    = Storage::url($store);

            //$imgurl = url('/').'/storage/app/'.$store;

            $imgurl3 = config('app.imgurl').basename($store);

        }



        $data = new Productvariant; 

        $data->color_id = $request->color_id;

        $data->size_id = $request->size_id;

        $data->price = $request->price;

        $data->product_id = $request->product_id;

        $data->imageurl    = $imgurl;

        $data->imageurl2    = $imgurl2;

        $data->imageurl3    = $imgurl3;

        $data->created_by=Auth::user()->id;

        $data->save();

        return redirect('/productvariants/'.$request->product_id);

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Models\Productvariant  $Productvariant

     * @return \Illuminate\Http\Response

     */

    public function show(Productvariant $Productvariant)

    {

        //

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Models\Productvariant  $Productvariant

     * @return \Illuminate\Http\Response

     */

    public function edit(Productvariant $Productvariant,$id)

    {   $title = "Product Variant";

        $log = Productvariant::where('id',$id)->first();

        $size = Variantsub::active()->where('variant_id','1')->get();

        $color = Variantsub::active()->where('variant_id','2')->get();

        return view('productvariant.edit',compact('title','log','size','color'));

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Models\Productvariant  $Productvariant

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, Productvariant $Productvariant)

    {
        $this->validate($request, [
            'editid' => 'required|exists:productvariants,id',
            'product_id' => 'required|exists:products,id',
            'color_id' => 'nullable|exists:variants_sub,id',
            'size_id' => 'nullable|exists:variants_sub,id',
            'price' => 'required|numeric|min:0',
            'imgfile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'imgfile2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'imgfile3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'imgfile_val' => 'nullable|string',
            'imgfile_val2' => 'nullable|string',
            'imgfile_val3' => 'nullable|string',
        ]);



        $imgurl    = '';

        $path   = $request->file('imgfile');



        if (!empty($path)) {

            $store  = Storage::putFile('public/image', $path);

            //$imgurl    = Storage::url($store);

            //$imgurl = url('/').'/storage/app/'.$store;

            $imgurl = config('app.imgurl').basename($store);

        }

        else{

            $imgurl=$request->imgfile_val;

        }



        $imgurl2    = '';

        $path   = $request->file('imgfile2');



        if (!empty($path)) {

            $store  = Storage::putFile('public/image', $path);

            //$imgurl    = Storage::url($store);

            //$imgurl = url('/').'/storage/app/'.$store;

            $imgurl2 = config('app.imgurl').basename($store);

        }

        else{

            $imgurl2=$request->imgfile_val2;

        }



        $imgurl3    = '';

        $path   = $request->file('imgfile3');



        if (!empty($path)) {

            $store  = Storage::putFile('public/image', $path);

            //$imgurl    = Storage::url($store);

            //$imgurl = url('/').'/storage/app/'.$store;

            $imgurl3 = config('app.imgurl').basename($store);

        }

        else{

            $imgurl3=$request->imgfile_val3;

        }



        $data = Productvariant::find($request->editid);

        if (empty($data)) { 
            return redirect('/productvariants/'.$request->product_id)->with('error', 'Product variant not found.');
        }

        

        $data->color_id = $request->color_id;

        $data->size_id = $request->size_id;

        $data->price = $request->price;

        $data->product_id = $request->product_id;

        $data->imageurl    = $imgurl;

        $data->created_by=Auth::user()->id;

        $data->save();

        $this->updatecartprices($request->product_id,$request->editid,$request->price);
        
         return redirect('/productvariants/'.$request->product_id);

    }


    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Models\Productvariant  $Productvariant

     * @return \Illuminate\Http\Response

     */

    public function destroy(Productvariant $Productvariant,$id,$product_id)

    {

        $data = Productvariant::find($id);

        $data->delete_status = 1;

        $data->save();

        return redirect('/productvariants/'.$product_id);

    }

    public function updatecartprices($product_id='',$variant_id,$actualprice)
    {
        $carts =  Cart::where('product_id',$product_id)->where('variant_id',$variant_id)->where('carted','0')->where('delete_status',0)->get();
        foreach ($carts as $cart) {
            $totalprice = $actualprice*$cart->qty;
            Cart::where('id',$cart->id)->update(['actual_price'=>$actualprice,'total_price'=>$totalprice]);
        }
    }

}

