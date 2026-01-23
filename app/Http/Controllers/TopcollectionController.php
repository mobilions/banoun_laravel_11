<?php



namespace App\Http\Controllers;



use App\Models\Category;

use App\Models\Product;

use App\Models\Subcategory;

use App\Models\Brand;

use App\Models\Topcollection;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use Auth;

use DB;



class TopcollectionController extends Controller

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



    public function index()
    {
        $title = "Top Banner";

        $indexes = Topcollection::query()
            ->leftJoin('categories', function ($join) {
                $join->on('topcollections.shopby', '=', 'categories.id')
                    ->where('topcollections.type', 'category');
            })
            ->leftJoin('products', function ($join) {
                $join->on('topcollections.shopby', '=', 'products.id')
                    ->where('topcollections.type', 'product');
            })
            ->select([
                'topcollections.*',
                \DB::raw("
                    CASE 
                        WHEN topcollections.type = 'category' THEN categories.name
                        WHEN topcollections.type = 'product' THEN products.name
                    END as name
                "),
                \DB::raw("
                    CASE 
                        WHEN topcollections.type = 'category' THEN categories.name_ar
                        WHEN topcollections.type = 'product' THEN products.name_ar
                    END as name_ar
                ")
            ])
            ->where('topcollections.delete_status', '0')
            ->orderByDesc('topcollections.id')
            ->get();

        return view('topcollection.index', compact('title', 'indexes'));
    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        $title = "Top Banner";

        $cattype=DB::table('banner_url')->get();

        $categories = Category::where('delete_status','0')->get();

        $subcategories = Subcategory::where('delete_status','0')->get();

        $brands = Brand::where('delete_status','0')->get();

        $products=Product::where('delete_status','0')->get();

        return view('topcollection.create',compact('title','categories','subcategories','brands','cattype','products')); 

    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)

    {
        $rules = [
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'redirect_type' => 'required|string',
            'imgfile' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048|dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000',
        ];

        // If redirect_type is productlist, shopby and category_id are required
        // if ($request->redirect_type == 'productlist') {
        //     $rules['shopby'] = 'required|string|in:category,subcategory,brand,product';
        //     $rules['category_id'] = 'required|exists:categories,id';
        // } else {
        //     $rules['shopby'] = 'nullable|string|in:category,subcategory,brand,product';
        //     $rules['category_id'] = 'nullable|exists:categories,id';
        // }

        $this->validate($request, $rules, [
            'name.required' => 'Name is required.',
            'name.max' => 'Name must not exceed 255 characters.',
            'name_ar.max' => 'Arabic name must not exceed 255 characters.',
            'redirect_type.required' => 'Redirect type is required.',
            // 'shopby.required' => 'Shop by is required when redirect type is Product List.',
            // 'shopby.in' => 'Invalid shop by value.',
            // 'category_id.required' => 'Category is required when redirect type is Product List.',
            // 'category_id.exists' => 'Selected category does not exist.',
            'imgfile.required' => 'Image is required.',
            'imgfile.image' => 'The file must be an image.',
            'imgfile.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'imgfile.max' => 'The image size must not exceed 2MB.',
            'imgfile.dimensions' => 'The image dimensions must be between 100x100 and 4000x4000 pixels.',
        ]);

        try {
            $imgurl = '';

            $path = $request->file('imgfile');

            if (!empty($path) && $path->isValid()) {
                $storedPath  = $path->store('image', 'public');
                $imgurl = 'storage/'.$storedPath;
            }

            $redirect_type = $request->redirect_type;
            $redirect_by = null;

            if($redirect_type == 'productlist'){
                if($request->shopby == 'category'){
                    $redirect_by = 'categoryId';
                } elseif($request->shopby == 'subcategory'){
                    $redirect_by = 'subcategoryId';
                } elseif($request->shopby == 'brand'){
                    $redirect_by = 'brandId';
                } elseif($request->shopby == 'product'){
                    $redirect_by = 'productId';
                }
            } else {
                $bannerUrl = DB::table('banner_url')->where('base_url', $redirect_type)->first();
                if ($bannerUrl) {
                    $redirect_by = $bannerUrl->base_id;
                } else {
                    return redirect()->back()->withInput()->with('error', 'Invalid redirect type selected.');
                }
            }

            $data = new Topcollection; 
            $data->name = $request->name;
            $data->description = $request->description;
            $data->name_ar = $request->name_ar;
            $data->description_ar = $request->description_ar;
            $data->imageurl = $imgurl;
            $data->shopby = $request->shopby;
            $data->category_id = $request->category_id;
            $data->type = $request->category_id;
            $data->redirect_type = $redirect_type;
            $data->redirect_by = $redirect_by;
            $data->delete_status = '0';
            $data->created_by = Auth::user()->id;
            $data->save();

            return redirect('/topcollection')->with('success', 'Top banner created successfully.');
        } catch (\Exception $e) {
            \Log::error('Top banner creation failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create top banner. Please try again.');
        }

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Models\Topcollection  $topcollection

     * @return \Illuminate\Http\Response

     */

    public function show(Topcollection $topcollection)

    {

        

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Models\Topcollection  $topcollection

     * @return \Illuminate\Http\Response

     */

    public function edit(Topcollection $topcollection,$id)

    {

        $title = "Topcollection";

        $log = Topcollection::where('id',$id)->first();

        $categories = Category::where('delete_status','0')->get();

        $subcategories = Subcategory::where('delete_status','0')->get();

        $brands = Brand::where('delete_status','0')->get();

        $cattype=DB::table('banner_url')->get();

        $products=Product::where('delete_status','0')->get();

        return view('topcollection.edit',compact('title','log','categories','subcategories','brands','cattype','products'));  

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Models\Topcollection  $topcollection

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, Topcollection $topcollection)

    {
        $rules = [
            'editid' => 'required|exists:topcollections,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'redirect_type' => 'required|string',
            'imgfile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048|dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000',
            'imgfile_val' => 'nullable|string',
        ];

        // If redirect_type is productlist, shopby and category_id are required
        // if ($request->redirect_type == 'productlist') {
        //     $rules['shopby'] = 'required|string|in:category,subcategory,brand,product';
        //     $rules['category_id'] = 'required|exists:categories,id';
        // } else {
        //     $rules['shopby'] = 'nullable|string|in:category,subcategory,brand,product';
        //     $rules['category_id'] = 'nullable|exists:categories,id';
        // }

        $this->validate($request, $rules, [
            'editid.required' => 'Record ID is required.',
            'editid.exists' => 'Selected record does not exist.',
            'name.required' => 'Name is required.',
            'name.max' => 'Name must not exceed 255 characters.',
            'name_ar.max' => 'Arabic name must not exceed 255 characters.',
            'redirect_type.required' => 'Redirect type is required.',
            // 'shopby.required' => 'Shop by is required when redirect type is Product List.',
            // 'shopby.in' => 'Invalid shop by value.',
            // 'category_id.required' => 'Category is required when redirect type is Product List.',
            // 'category_id.exists' => 'Selected category does not exist.',
            'imgfile.image' => 'The file must be an image.',
            'imgfile.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'imgfile.max' => 'The image size must not exceed 2MB.',
            'imgfile.dimensions' => 'The image dimensions must be between 100x100 and 4000x4000 pixels.',
        ]);

        $data = Topcollection::find($request->editid);

        if (empty($data)) { 
            return redirect('/topcollection')->with('error', 'Top collection not found.');
        }

        try {
            $imgurl = '';

            $path = $request->file('imgfile');

            if (!empty($path) && $path->isValid()) {
                $storedPath  = $path->store('image', 'public');
                $imgurl = 'storage/'.$storedPath;
            } else {
                $imgurl = $request->imgfile_val ?? '';
            }

            $redirect_type = $request->redirect_type;
            $redirect_by = null;

            if($redirect_type == 'productlist'){
                if($request->shopby == 'category'){
                    $redirect_by = 'categoryId';
                } elseif($request->shopby == 'subcategory'){
                    $redirect_by = 'subcategoryId';
                } elseif($request->shopby == 'brand'){
                    $redirect_by = 'brandId';
                } elseif($request->shopby == 'product'){
                    $redirect_by = 'productId';
                }
            } else {
                $bannerUrl = DB::table('banner_url')->where('base_url', $redirect_type)->first();
                if ($bannerUrl) {
                    $redirect_by = $bannerUrl->base_id;
                } else {
                    return redirect()->back()->withInput()->with('error', 'Invalid redirect type selected.');
                }
            }

            $data->name = $request->name;
            $data->description = $request->description;
            $data->name_ar = $request->name_ar;
            $data->description_ar = $request->description_ar;
            $data->imageurl = $imgurl;
            $data->shopby = $request->shopby;
            $data->redirect_type = $redirect_type;
            $data->redirect_by = $redirect_by;
            $data->category_id = $request->category_id;
            $data->type = $request->category_id;
            $data->updated_by = Auth::user()->id;
            $data->save();

            return redirect('/topcollection')->with('success', 'Top banner updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Top banner update failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update top banner. Please try again.');
        }

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Models\Topcollection  $subTopcollection

     * @return \Illuminate\Http\Response

     */

    public function destroy(Topcollection $topcollection,$id)

    {
        $data = Topcollection::find($id);

        if (empty($data)) {
            return redirect('/topcollection')->with('error', 'Top banner not found.');
        }

        try {
            $data->delete_status = 1;
            $data->save();

            return redirect('/topcollection')->with('success', 'Top banner deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Top banner deletion failed: ' . $e->getMessage());
            return redirect('/topcollection')->with('error', 'Failed to delete top banner. Please try again.');
        }

    }

}

