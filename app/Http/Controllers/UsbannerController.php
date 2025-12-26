<?php



namespace App\Http\Controllers;



use App\Models\Category;

use App\Models\Subcategory;

use App\Models\Brand;

use App\Models\Product;

use App\Models\Usbanner;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use Auth;

use DB;



class UsbannerController extends Controller

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

        $title = "Promotion Banner";

        $indexes = Usbanner::where('delete_status','0')->get();

        return view('usbanner.index',compact('title','indexes'));  

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        $title = "Promotion Banner";

        $categories = Category::where('delete_status','0')->get();

        $subcategories = Subcategory::where('delete_status','0')->get();

        $brands = Brand::where('delete_status','0')->get();

        $products=Product::where('delete_status','0')->get();

        $cattype=DB::table('banner_url')->get();

        return view('usbanner.create',compact('title','categories','subcategories','brands','cattype','products')); 

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
            'grid' => 'nullable|string|in:1,2',
            'redirect_type' => 'required|string',
            'imgfile' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048|dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000',
            'imgfile_sm' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ];

        // If redirect_type is productlist, shopby and category_id are required
        if ($request->redirect_type == 'productlist') {
            $rules['shopby'] = 'required|string|in:category,subcategory,brand,product';
            $rules['category_id'] = 'required|exists:categories,id';
        } else {
            $rules['shopby'] = 'nullable|string|in:category,subcategory,brand,product';
            $rules['category_id'] = 'nullable|exists:categories,id';
        }

        $this->validate($request, $rules, [
            'name.required' => 'Name is required.',
            'name.max' => 'Name must not exceed 255 characters.',
            'name_ar.max' => 'Arabic name must not exceed 255 characters.',
            'grid.in' => 'Grid must be 1 (Big) or 2 (Small).',
            'redirect_type.required' => 'Redirect type is required.',
            'shopby.required' => 'Shop by is required when redirect type is Product List.',
            'shopby.in' => 'Invalid shop by value.',
            'category_id.required' => 'Category is required when redirect type is Product List.',
            'category_id.exists' => 'Selected category does not exist.',
            'imgfile.required' => 'Image is required.',
            'imgfile.image' => 'The file must be an image.',
            'imgfile.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'imgfile.max' => 'The image size must not exceed 2MB.',
            'imgfile.dimensions' => 'The image dimensions must be between 100x100 and 4000x4000 pixels.',
            'imgfile_sm.image' => 'The small image file must be an image.',
            'imgfile_sm.mimes' => 'The small image must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'imgfile_sm.max' => 'The small image size must not exceed 2MB.',
        ]);

        try {
            $imgurl_sm = '';
            $path_sm = $request->file('imgfile_sm');

            if (!empty($path_sm) && $path_sm->isValid()) {
                $storedPath  = $path_sm->store('image', 'public');
                $imgurl_sm = 'storage/'.$storedPath;
            }

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

            $data = new Usbanner; 
            $data->name = $request->name;
            $data->description = $request->description;
            $data->name_ar = $request->name_ar;
            $data->description_ar = $request->description_ar;
            $data->grid = $request->grid;
            $data->imageurl = $imgurl;
            $data->image_sm = $imgurl_sm;
            $data->shopby = $request->shopby;
            $data->redirect_type = $redirect_type;
            $data->redirect_by = $redirect_by;
            $data->category_id = $request->category_id;
            $data->delete_status = '0';
            $data->created_by = Auth::user()->id;
            $data->save();

            return redirect('/usbanner')->with('success', 'Promotion banner created successfully.');
        } catch (\Exception $e) {
            \Log::error('Promotion banner creation failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create promotion banner. Please try again.');
        }

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\usbanner  $usbanner

     * @return \Illuminate\Http\Response

     */

    public function show(Usbanner $usbanner)

    {

        

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\usbanner  $usbanner

     * @return \Illuminate\Http\Response

     */

    public function edit(Usbanner $usbanner,$id)

    {

        $title = "Promotion Banner";

        $log = Usbanner::where('id',$id)->first();

        $categories = Category::where('delete_status','0')->get();

        $subcategories = Subcategory::where('delete_status','0')->get();

        $brands = Brand::where('delete_status','0')->get();

        $products=Product::where('delete_status','0')->get();

        $cattype=DB::table('banner_url')->get();

        return view('usbanner.edit',compact('title','log','categories','subcategories','brands','cattype','products'));  

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\usbanner  $usbanner

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, Usbanner $usbanner)

    {
        $rules = [
            'editid' => 'required|exists:usbanners,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'grid' => 'nullable|string|in:1,2',
            'redirect_type' => 'required|string',
            'imgfile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048|dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000',
            'imgfile_sm' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'imgfile_val' => 'nullable|string',
            'imgfile_val_sm' => 'nullable|string',
        ];

        // If redirect_type is productlist, shopby and category_id are required
        if ($request->redirect_type == 'productlist') {
            $rules['shopby'] = 'required|string|in:category,subcategory,brand,product';
            $rules['category_id'] = 'required|exists:categories,id';
        } else {
            $rules['shopby'] = 'nullable|string|in:category,subcategory,brand,product';
            $rules['category_id'] = 'nullable|exists:categories,id';
        }

        $this->validate($request, $rules, [
            'editid.required' => 'Record ID is required.',
            'editid.exists' => 'Selected record does not exist.',
            'name.required' => 'Name is required.',
            'name.max' => 'Name must not exceed 255 characters.',
            'name_ar.max' => 'Arabic name must not exceed 255 characters.',
            'grid.in' => 'Grid must be 1 (Big) or 2 (Small).',
            'redirect_type.required' => 'Redirect type is required.',
            'shopby.required' => 'Shop by is required when redirect type is Product List.',
            'shopby.in' => 'Invalid shop by value.',
            'category_id.required' => 'Category is required when redirect type is Product List.',
            'category_id.exists' => 'Selected category does not exist.',
            'imgfile.image' => 'The file must be an image.',
            'imgfile.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'imgfile.max' => 'The image size must not exceed 2MB.',
            'imgfile.dimensions' => 'The image dimensions must be between 100x100 and 4000x4000 pixels.',
            'imgfile_sm.image' => 'The small image file must be an image.',
            'imgfile_sm.mimes' => 'The small image must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'imgfile_sm.max' => 'The small image size must not exceed 2MB.',
        ]);

        $data = Usbanner::find($request->editid);

        if (empty($data)) { 
            return redirect('/usbanner')->with('error', 'Promotion banner not found.');
        }

        try {
            $imgurl_sm = '';
            $path_sm = $request->file('imgfile_sm');

            if (!empty($path_sm) && $path_sm->isValid()) {
                $storedPath  = $path_sm->store('image', 'public');
                $imgurl_sm = 'storage/'.$storedPath;
            } else {
                $imgurl_sm = $request->imgfile_val_sm ?? '';
            }

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
            $data->grid = $request->grid;
            $data->imageurl = $imgurl;
            $data->image_sm = $imgurl_sm;
            $data->shopby = $request->shopby;
            $data->redirect_type = $redirect_type;
            $data->redirect_by = $redirect_by;
            $data->category_id = $request->category_id;
            $data->updated_by = Auth::user()->id;
            $data->save();

            return redirect('/usbanner')->with('success', 'Promotion banner updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Promotion banner update failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update promotion banner. Please try again.');
        }

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\usbanner  $subusbanner

     * @return \Illuminate\Http\Response

     */

    public function destroy(Usbanner $usbanner,$id)

    {
        $data = Usbanner::find($id);

        if (empty($data)) {
            return redirect('/usbanner')->with('error', 'Promotion banner not found.');
        }

        try {
            $data->delete_status = 1;
            $data->save();

            return redirect('/usbanner')->with('success', 'Promotion banner deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Promotion banner deletion failed: ' . $e->getMessage());
            return redirect('/usbanner')->with('error', 'Failed to delete promotion banner. Please try again.');
        }

    }

}

