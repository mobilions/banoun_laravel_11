<?php



namespace App\Http\Controllers;



use App\Models\Product;

use App\Models\Productimage;

use App\Models\Searchtag;

use App\Models\Cart;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Productvariant;



class ProductController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()

    {

        $title = "Product";

        $indexes = Product::active()->get();

        return view('product.index',compact('title','indexes'));  

    }



    public function productsearch()

    {

        $title = "Product Search Filter";

        $indexes = Product::active()->get();

        $content = view('product.product',compact('indexes'))->render();

        return view('product.search',compact('title','indexes','content'));  

    }



    public function updateproductsearch(Request $request)

    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'field' => 'required|string|in:is_newarrival,is_trending,is_topsearch,is_recommended',
            'value' => 'required|boolean',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $product->{$validated['field']} = $validated['value'];
        $product->updated_by = Auth::id();
        $product->save();

        return response()->json(['success' => true]);

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        $title = "Product";

        $categories = \App\Models\Category::active()->get();

        $subcategories = \App\Models\Subcategory::active()->get();

        $brands = \App\Models\Brand::active()->get();

        $colors = \App\Models\Variantsub::active()->where('variant_id','2')->get();

        $sizes = \App\Models\Variantsub::active()->where('variant_id','1')->get();

        $searchtags=Searchtag::active()->get();

        return view('product.create',compact('title','categories','subcategories','brands','colors','searchtags','sizes')); 

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
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'more_info' => 'nullable|string',
            'more_info_ar' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'price_offer' => 'required|numeric|min:0',
            'percentage_discount' => 'required|numeric|min:0|max:100',
            'color_id' => 'required|array|min:1',
            'color_id.*' => 'exists:variants_sub,id',
            'size_id' => 'required|array|min:1',
            'size_id.*' => 'exists:variants_sub,id',
            'searchtag_id' => 'required|array|min:1',
            'searchtag_id.*' => 'exists:searchtags,id',
            'imgfile' => ['required','image','mimes:jpeg,png,jpg,gif,svg,webp','max:2048','dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000'],
            'imgfile2' => ['nullable','image','mimes:jpeg,png,jpg,gif,svg,webp','max:2048','dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000'],
            'imgfile3' => ['nullable','image','mimes:jpeg,png,jpg,gif,svg,webp','max:2048','dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000'],
        ]);



        $imgurl    = $this->storeImageFile($request->file('imgfile')) ?? '';

        $imgurl2    = $this->storeImageFile($request->file('imgfile2')) ?? '';

        $imgurl3    = $this->storeImageFile($request->file('imgfile3')) ?? '';





        //exit();

        $is_newarrival=0;

        $is_trending=0;

        $is_recommended=0;

        $is_topsearch=0;

        if($request->is_newarrival=='on'){

            $is_newarrival=1;

        }

        if($request->is_trending=='on'){

            $is_trending=1;

        }

        if($request->is_recommended=='on'){

            $is_recommended=1;

        }

        if($request->is_topsearch=='on'){

            $is_topsearch=1;

        }



        $colorIds = collect($request->color_id)->filter()->implode(',');
        $sizeIds = collect($request->size_id)->filter()->implode(',');
        $searchtagIds = collect($request->searchtag_id)->filter()->implode(',');

        DB::transaction(function () use (
            $request,
            $imgurl,
            $imgurl2,
            $imgurl3,
            $is_newarrival,
            $is_trending,
            $is_recommended,
            $is_topsearch,
            $colorIds,
            $sizeIds,
            $searchtagIds
        ) {
            $product = new Product; 
            $product->category_id = $request->category_id;
            $product->subcategory_id = $request->subcategory_id;
            $product->brand_id = $request->brand_id;
            $product->name = $request->name;
            $product->imageurl = $imgurl;
            $product->description = $request->description;
            $product->name_ar = $request->name_ar;
            $product->description_ar = $request->description_ar;
            $product->more_info = $request->more_info;
            $product->more_info_ar = $request->more_info_ar;
            $product->price = $request->price;
            $product->colors = $colorIds;
            $product->size = $sizeIds;
            $product->searchtag_id = $searchtagIds;
            $product->price_offer = $request->price_offer;
            $product->percentage_discount = $request->percentage_discount;
            $product->is_newarrival = $is_newarrival;
            $product->is_trending = $is_trending;
            $product->is_recommended = $is_recommended;
            $product->is_topsearch = $is_topsearch;
            $product->created_by = Auth::user()->id;
            $product->save();

            $product_id = $product->id;

            $primaryImage = new Productimage; 
            $primaryImage->product_id = $product_id;
            $primaryImage->imageurl = $imgurl;
            $primaryImage->created_by = Auth::user()->id;
            $primaryImage->save();

            if(!empty($request->file('imgfile2')) && $imgurl2){
                $secondImage = new Productimage; 
                $secondImage->product_id = $product_id;
                $secondImage->imageurl = $imgurl2;
                $secondImage->created_by = Auth::user()->id;
                $secondImage->save();
            }
            

            if(!empty($request->file('imgfile3')) && $imgurl3){
                $thirdImage = new Productimage; 
                $thirdImage->product_id = $product_id;
                $thirdImage->imageurl = $imgurl3;
                $thirdImage->created_by = Auth::user()->id;
                $thirdImage->save();
            }
        });

        return redirect('/product');

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Models\Product  $product

     * @return \Illuminate\Http\Response

     */

    public function show(Product $product)

    {

        //

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Models\Product  $product

     * @return \Illuminate\Http\Response

     */

    public function edit(Product $product,$id)

    {

        $title = "Product";

        $log = Product::where('id',$id)->first();

        $categories = \App\Models\Category::active()->get();

        $subcategories = \App\Models\Subcategory::active()->get();

        $brands = \App\Models\Brand::active()->get();

        $productvariants = Productvariant::active()->where('product_id',$id)->count();

        $colors = \App\Models\Variantsub::active()->where('variant_id','2')->get();

        $searchtags=Searchtag::active()->get();

        $sizes = \App\Models\Variantsub::active()->where('variant_id','1')->get();

        $productvariantimages=Productimage::where('product_id',$id)->where('delete_status','0')->get();

        return view('product.edit',compact('title','log','categories','subcategories','brands','productvariants','colors','searchtags','sizes','productvariantimages'));

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Models\Product  $product

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, Product $product)

    {
        $this->validate($request, [
            'editid' => 'required|exists:products,id',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'more_info' => 'nullable|string',
            'more_info_ar' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'price_offer' => 'required|numeric|min:0',
            'percentage_discount' => 'required|numeric|min:0|max:100',
            'color_id' => 'required|array|min:1',
            'color_id.*' => 'exists:variants_sub,id',
            'size_id' => 'required|array|min:1',
            'size_id.*' => 'exists:variants_sub,id',
            'searchtag_id' => 'required|array|min:1',
            'searchtag_id.*' => 'exists:searchtags,id',
            'imgfile' => ['nullable','image','mimes:jpeg,png,jpg,gif,svg,webp','max:2048','dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000'],
            'imgfile2' => ['nullable','image','mimes:jpeg,png,jpg,gif,svg,webp','max:2048','dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000'],
            'imgfile3' => ['nullable','image','mimes:jpeg,png,jpg,gif,svg,webp','max:2048','dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000'],
        ]);

        $is_newarrival=0;

        $is_trending=0;

        $is_recommended=0;

        $is_topsearch=0;

        if($request->is_newarrival=='on'){

            $is_newarrival=1;

        }

        if($request->is_trending=='on'){

            $is_trending=1;

        }

        if($request->is_recommended=='on'){

            $is_recommended=1;

        }

        if($request->is_topsearch=='on'){

            $is_topsearch=1;

        }



        $data = Product::find($request->editid);

        if (empty($data)) { 
            return redirect('/product')->with('error', 'Product not found.');
        }

        $colorIds = collect($request->color_id)->filter()->implode(',');
        $sizeIds = collect($request->size_id)->filter()->implode(',');
        $searchtagIds = collect($request->searchtag_id)->filter()->implode(',');

        DB::transaction(function () use (
            $data,
            $request,
            $is_newarrival,
            $is_trending,
            $is_recommended,
            $is_topsearch,
            $colorIds,
            $sizeIds,
            $searchtagIds
        ) {
            $data->category_id = $request->category_id;
            $data->subcategory_id = $request->subcategory_id;
            $data->brand_id = $request->brand_id;
            $data->name = $request->name;
            $data->description = $request->description;
            $data->name_ar = $request->name_ar;
            $data->description_ar = $request->description_ar;
            $data->more_info = $request->more_info;
            $data->more_info_ar = $request->more_info_ar;
            $data->price = $request->price;
            $data->colors = $colorIds;
            $data->searchtag_id = $searchtagIds;
            $data->size = $sizeIds;
            $data->price_offer = $request->price_offer;
            $data->percentage_discount = $request->percentage_discount;
            $data->is_newarrival = $is_newarrival;
            $data->is_trending = $is_trending;
            $data->is_recommended = $is_recommended;
            $data->is_topsearch = $is_topsearch;
            $data->created_by = Auth::user()->id;
            $data->save();

            $this->updatecartprices($request->editid,0,$request->price);
        });

        return redirect('/product');

    }

    private function storeImageFile($file): ?string
    {
        if (!$file) {
            return null;
        }

        $storedPath = $file->store('image', 'public');

        return 'storage/'.$storedPath;
    }

    
    public function updatecartprices($product_id,$variant_id,$actualprice)
    {
    	$carts =  Cart::where('product_id',$product_id)->where('variant_id',$variant_id)->where('carted','0')->where('delete_status',0)->get();
    	foreach ($carts as $cart) {
    		$totalprice = $actualprice*$cart->qty;

    		Cart::where('id',$cart->id)->update(['actual_price'=>$actualprice,'total_price'=>$totalprice]);
    	}
    }

    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Models\Product  $product

     * @return \Illuminate\Http\Response

     */

    public function destroy(Product $product,$id)

    {

        $data = Product::find($id);

        $data->delete_status = 1;

        $data->save();

        return redirect('/product');

    }



    public function productvimage(Request $request)

    {    

        

        $this->validate($request, ['imgfile' => 'image|mimes:jpeg,png,jpg,gif,svg',]);



        $imgurl    = $this->storeImageFile($request->file('imgfile')) ?? '';

        

        $data = new Productimage; 

        $data->product_id = $request->product_id;

        $data->imageurl    = $imgurl;

        $data->created_by=Auth::user()->id;

        $data->save();

        

        return redirect()->back();

    }



     public function destroyproductvimage($id)

    {

        $data = Productimage::find($id);

        $data->delete_status = 1;

        $data->save();

        return redirect()->back();

    }

}

