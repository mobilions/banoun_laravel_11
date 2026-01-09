<?php



namespace App\Http\Controllers;



use App\Models\Product;

use App\Models\ProductImage;

use App\Models\Searchtag;

use App\Models\Cart;

use App\Models\Variant;

use App\Rules\PriceOfferRule;

use App\Rules\PercentageDiscountRule;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Productvariant;



class ProductController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index(Request $request)

    {
        $title = "Product";

        $query = Product::active()
            ->with(['category', 'brand', 'subcategory'])
            ->orderByDesc('created_at');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('name_ar', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by brand
        if ($request->has('brand_id') && $request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filter by price range
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Pagination
        $indexes = $query->paginate(15)->withQueryString();

        // For filters dropdown
        $categories = \App\Models\Category::active()->get();
        $brands = \App\Models\Brand::active()->get();

        return view('product.index', compact('title', 'indexes', 'categories', 'brands'));  

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

        // Get only categories that have at least one subcategory
        $categories = \App\Models\Category::where('delete_status', 0)
                        ->whereHas('subcategories', function($query) {
                            $query->where('delete_status', 0);
                        })
                        ->get();

        $subcategories = \App\Models\Subcategory::active()->get();

        $brands = \App\Models\Brand::active()->get();

        // Dynamic variant lookup instead of hardcoded IDs
        $sizeVariant = Variant::where('name', 'Size')->active()->first();
        $colorVariant = Variant::where('name', 'Color')->active()->first();
        
        $sizes = collect();
        $colors = collect();
        
        if ($sizeVariant) {
            $sizes = \App\Models\Variantsub::active()->where('variant_id', $sizeVariant->id)->get();
        }
        
        if ($colorVariant) {
            $colors = \App\Models\Variantsub::active()->where('variant_id', $colorVariant->id)->get();
        }

        $searchtags = Searchtag::active()->get();

        return view('product.create', compact('title', 'categories', 'subcategories', 'brands', 'colors', 'searchtags', 'sizes')); 

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
            'price_offer' => 'required|numeric|min:0|lte:price',
            'percentage_discount' => 'required|numeric|min:0|max:100',
            'color_id' => 'required|array|min:1',
            'color_id.*' => 'exists:variants_sub,id',
            'size_id' => 'required|array|min:1',
            'size_id.*' => 'exists:variants_sub,id',
            'searchtag_id' => 'required|array|min:1',
            'searchtag_id.*' => 'exists:search_tags,id',
            'imgfile' => ['required','image','mimes:jpeg,png,jpg,gif,svg,webp','max:2048','dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000'],
            'imgfile2' => ['nullable','image','mimes:jpeg,png,jpg,gif,svg,webp','max:2048','dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000'],
            'imgfile3' => ['nullable','image','mimes:jpeg,png,jpg,gif,svg,webp','max:2048','dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000'],
        ], [
            'price_offer.lte' => 'Offer price cannot be greater than the regular price.',
            'imgfile.required' => 'Primary image is required.',
            'category_id.required' => 'Category is required.',
            'subcategory_id.required' => 'Subcategory is required.',
            'brand_id.required' => 'Brand is required.',
            'color_id.required' => 'At least one color must be selected.',
            'size_id.required' => 'At least one size must be selected.',
            'searchtag_id.required' => 'At least one search tag must be selected.',
        ]);



        try {
            $imgurl = $this->storeImageFile($request->file('imgfile')) ?? '';
            if (empty($imgurl)) {
                return redirect()->back()->withInput()->with('error', 'Failed to upload primary image. Please try again.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error uploading primary image: ' . $e->getMessage());
        }

        try {
            $imgurl2 = $this->storeImageFile($request->file('imgfile2')) ?? '';
        } catch (\Exception $e) {
            // Log error but don't fail if secondary image fails
            \Log::warning('Failed to upload secondary image: ' . $e->getMessage());
            $imgurl2 = '';
        }

        try {
            $imgurl3 = $this->storeImageFile($request->file('imgfile3')) ?? '';
        } catch (\Exception $e) {
            // Log error but don't fail if tertiary image fails
            \Log::warning('Failed to upload tertiary image: ' . $e->getMessage());
            $imgurl3 = '';
        }





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

        try {
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

            $primaryImage = new ProductImage; 
            $primaryImage->product_id = $product_id;
            $primaryImage->imageurl = $imgurl;
            $primaryImage->created_by = Auth::user()->id;
            $primaryImage->save();

            if(!empty($request->file('imgfile2')) && $imgurl2){
                $secondImage = new ProductImage; 
                $secondImage->product_id = $product_id;
                $secondImage->imageurl = $imgurl2;
                $secondImage->created_by = Auth::user()->id;
                $secondImage->save();
            }
            

            if(!empty($request->file('imgfile3')) && $imgurl3){
                $thirdImage = new ProductImage; 
                $thirdImage->product_id = $product_id;
                $thirdImage->imageurl = $imgurl3;
                $thirdImage->created_by = Auth::user()->id;
                $thirdImage->save();
            }
            });

            return redirect('/product')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            \Log::error('Product creation failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create product. Please try again.');
        }

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

        $log = Product::with(['category', 'brand', 'subcategory', 'variants', 'images'])
            ->where('id',$id)
            ->first();

        if (empty($log)) {
            return redirect('/product')->with('error', 'Product not found.');
        }

        // Get only categories that have at least one subcategory
        $categories = \App\Models\Category::where('delete_status', 0)
                        ->whereHas('subcategories', function($query) {
                    $query->where('delete_status', 0);
                })
                        ->get();

        $subcategories = \App\Models\Subcategory::active()->get();

        $brands = \App\Models\Brand::active()->get();

        $productvariants = Productvariant::active()->where('product_id',$id)->count();
        $productVariantList = Productvariant::active()
            ->where('product_id',$id)
            ->with(['sizeVariant', 'colorVariant'])
            ->orderByDesc('created_at')
            ->get();

        // Dynamic variant lookup instead of hardcoded IDs
        $sizeVariant = Variant::where('name', 'Size')->active()->first();
        $colorVariant = Variant::where('name', 'Color')->active()->first();
        
        $sizes = collect();
        $colors = collect();
        
        if ($sizeVariant) {
            $sizes = \App\Models\Variantsub::active()->where('variant_id', $sizeVariant->id)->get();
        }
        
        if ($colorVariant) {
            $colors = \App\Models\Variantsub::active()->where('variant_id', $colorVariant->id)->get();
        }

        $searchtags = Searchtag::active()->get();

        $productvariantimages=ProductImage::where('product_id',$id)
            ->where('delete_status','0')
            ->get()
            ->map(function ($image) {
                $image->display_url = $image->imageurl
                    ? (Str::startsWith($image->imageurl, ['http://','https://','//']) ? $image->imageurl : asset($image->imageurl))
                    : null;
                return $image;
            });

        return view('product.edit',compact('title','log','categories','subcategories','brands','productvariants','colors','searchtags','sizes','productvariantimages','productVariantList'));

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
        try {
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
                'price_offer' => 'required|numeric|min:0|lte:price',
                'percentage_discount' => 'required|numeric|min:0|max:100',
                'color_id' => 'required|array|min:1',
                'color_id.*' => 'exists:variants_sub,id',
                'size_id' => 'required|array|min:1',
                'size_id.*' => 'exists:variants_sub,id',
                'searchtag_id' => 'required|array|min:1',
                'searchtag_id.*' => 'exists:search_tags,id',
                'imgfile' => ['nullable','image','mimes:jpeg,png,jpg,gif,svg,webp','max:2048','dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000'],
                'imgfile2' => ['nullable','image','mimes:jpeg,png,jpg,gif,svg,webp','max:2048','dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000'],
                'imgfile3' => ['nullable','image','mimes:jpeg,png,jpg,gif,svg,webp','max:2048','dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000'],
            ], [
            'price_offer.lte' => 'Offer price cannot be greater than the regular price.',
            'category_id.required' => 'Category is required.',
            'subcategory_id.required' => 'Subcategory is required.',
            'brand_id.required' => 'Brand is required.',
            'color_id.required' => 'At least one color must be selected.',
            'size_id.required' => 'At least one size must be selected.',
            'searchtag_id.required' => 'At least one search tag must be selected.',
        ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation errors: ' . json_encode($e->errors()));
            throw $e;
        }

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

        try {
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

            return redirect('/product')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Product update failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update product. Please try again.');
        }

    }

    public function productvimage(Request $request)
{    
    \Log::info('=== PRODUCT IMAGE UPLOAD STARTED ===');
    \Log::info('Request data:', $request->all());
    \Log::info('Has file: ' . ($request->hasFile('imgfile') ? 'YES' : 'NO'));
    
    // Log request details
    if ($request->hasFile('imgfile')) {
        $file = $request->file('imgfile');
        \Log::info('File details:', [
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'extension' => $file->getClientOriginalExtension(),
            'is_valid' => $file->isValid(),
            'error' => $file->getError(),
            'temp_path' => $file->getRealPath(),
        ]);
    } else {
        \Log::error('No file in request!');
        return redirect()->back()->withErrors(['imgfile' => 'No file was uploaded']);
    }

    // Validate
    try {
        $this->validate($request, [
            'imgfile' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'product_id' => 'required|exists:products,id',
        ]);
        \Log::info('Validation passed');
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation failed:', $e->errors());
        return redirect()->back()->withErrors($e->errors());
    }

    // Check storage directory
    $storagePath = storage_path('app/public/image');
    \Log::info('Storage path:', [
        'path' => $storagePath,
        'exists' => file_exists($storagePath),
        'writable' => is_writable($storagePath),
        'permissions' => file_exists($storagePath) ? substr(sprintf('%o', fileperms($storagePath)), -4) : 'N/A',
    ]);

    // Try to create directory if missing
    if (!file_exists($storagePath)) {
        \Log::warning('Storage directory does not exist, attempting to create...');
        try {
            mkdir($storagePath, 0775, true);
            \Log::info('Directory created successfully');
        } catch (\Exception $e) {
            \Log::error('Failed to create directory: ' . $e->getMessage());
        }
    }

    // Store file
    try {
        \Log::info('Attempting to store file...');
        $imgurl = $this->storeImageFile($request->file('imgfile'));
        
        if (!$imgurl) {
            \Log::error('storeImageFile returned null or empty');
            return redirect()->back()->withErrors(['imgfile' => 'Failed to store image file']);
        }
        
        \Log::info('File stored successfully at: ' . $imgurl);
        
    } catch (\Exception $e) {
        \Log::error('Exception during file storage:', [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);
        return redirect()->back()->withErrors(['imgfile' => 'Storage error: ' . $e->getMessage()]);
    }

    // Save to database
    try {
        \Log::info('Saving to database...');
        
        $data = new ProductImage; 
        $data->product_id = $request->product_id;
        $data->imageurl = $imgurl;
        $data->created_by = Auth::user()->id;
        $data->save();
        
        \Log::info('Database record created with ID: ' . $data->id);
        \Log::info('=== PRODUCT IMAGE UPLOAD COMPLETED SUCCESSFULLY ===');
        
        return redirect()->back()->with('success', 'Image uploaded successfully!');
        
    } catch (\Exception $e) {
        \Log::error('Database save failed:', [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'trace' => $e->getTraceAsString(),
        ]);
        
        // Try to delete the uploaded file if DB save fails
        if ($imgurl) {
            try {
                $cleanPath = str_replace('storage/', '', $imgurl);
                Storage::disk('public')->delete($cleanPath);
                \Log::info('Cleaned up uploaded file after DB failure');
            } catch (\Exception $cleanupError) {
                \Log::error('Failed to cleanup file: ' . $cleanupError->getMessage());
            }
        }
        
        return redirect()->back()->withErrors(['imgfile' => 'Database error: ' . $e->getMessage()]);
    }
}

private function storeImageFile($file): ?string
{
    \Log::info('=== storeImageFile method called ===');
    
    if (!$file) {
        \Log::error('File parameter is null');
        return null;
    }

    \Log::info('File received:', [
        'name' => $file->getClientOriginalName(),
        'size' => $file->getSize(),
        'valid' => $file->isValid(),
    ]);

    try {
        // Check if public disk is configured
        $diskConfig = config('filesystems.disks.public');
        \Log::info('Public disk config:', $diskConfig);
        
        // Attempt to store
        \Log::info('Calling file->store()...');
        $storedPath = $file->store('image', 'public');
        
        \Log::info('file->store() returned: ' . ($storedPath ?: 'NULL'));
        
        if (!$storedPath) {
            \Log::error('store() method returned empty value');
            return null;
        }
        
        // Verify file was actually created
        $fullPath = storage_path('app/public/' . $storedPath);
        \Log::info('Checking if file exists at: ' . $fullPath);
        \Log::info('File exists: ' . (file_exists($fullPath) ? 'YES' : 'NO'));
        
        if (file_exists($fullPath)) {
            \Log::info('File size on disk: ' . filesize($fullPath) . ' bytes');
            \Log::info('File permissions: ' . substr(sprintf('%o', fileperms($fullPath)), -4));
        }
        
        $returnPath = 'storage/' . $storedPath;
        \Log::info('Returning path: ' . $returnPath);
        
        return $returnPath;
        
    } catch (\Exception $e) {
        \Log::error('Exception in storeImageFile:', [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);
        return null;
    }
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

        if (empty($data)) {
            return redirect('/product')->with('error', 'Product not found.');
        }

        $data->delete_status = 1;

        $data->save();

        return redirect('/product')->with('success', 'Product deleted successfully.');

    }


     public function destroyproductvimage($id)

    {

        $data = ProductImage::find($id);

        $data->delete_status = 1;

        $data->save();

        return redirect()->back();

    }

}

