<?php



namespace App\Http\Controllers;



use App\Models\Category;

use App\Models\Product;

use App\Models\Subcategory;

use App\Models\Brand;
use App\Models\Searchtag;
use App\Models\Topcollection;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
            $join->on('categories.id', '=', 'topcollections.type')
                ->where('topcollections.shopby', 'category');
        })
        ->leftJoin('subcategories', function ($join) {
            $join->on('subcategories.id', '=', 'topcollections.type')
                ->where('topcollections.shopby', 'subcategory');
        })
        ->leftJoin('brands', function ($join) {
            $join->on('brands.id', '=', 'topcollections.type')
                ->where('topcollections.shopby', 'brand');
        })
        ->leftJoin('products', function ($join) {
            $join->on('products.id', '=', 'topcollections.type')
                ->where('topcollections.shopby', 'product');
        })
        ->where('topcollections.delete_status', 0)
        ->orderByDesc('topcollections.id')
        ->select([
            'topcollections.id',
            'topcollections.shopby',
            'topcollections.type',
            'topcollections.imageurl',
            'topcollections.redirect_type',
            'topcollections.url',
            'topcollections.name as banner_name',
            'topcollections.name_ar as banner_name_ar',

            // Pick name based on shopby
            DB::raw("
                CASE
                    WHEN topcollections.shopby = 'category' THEN categories.name
                    WHEN topcollections.shopby = 'subcategory' THEN subcategories.name
                    WHEN topcollections.shopby = 'brand' THEN brands.name
                    WHEN topcollections.shopby = 'product' THEN products.name
                END AS source_name
            "),

            DB::raw("
                CASE
                    WHEN topcollections.shopby = 'category' THEN categories.name_ar
                    WHEN topcollections.shopby = 'subcategory' THEN subcategories.name_ar
                    WHEN topcollections.shopby = 'brand' THEN brands.name_ar
                    WHEN topcollections.shopby = 'product' THEN products.name_ar
                END AS source_name_ar
            "),
        ])
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
            'redirect_type' => 'required|string|in:Category,Product listing,Product detail,URL',
            'imgfile' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ];

        if ($request->redirect_type == 'URL') {
            $rules['url'] = 'required|url';
        } else {
            $rules['shopby'] = 'required|string|in:category,subcategory,brand,product';
            $rules['type'] = 'required|integer';
            if ($request->shopby == 'subcategory' && $request->redirect_type == 'Product listing') {
                $rules['parent_category_id'] = 'required|integer|exists:categories,id';
            }
        }

        $this->validate($request, $rules, [
            'name.required' => 'Name is required.',
            'redirect_type.required' => 'Redirect type is required.',
            'redirect_type.in' => 'Invalid redirect type.',
            'shopby.required' => 'Shop by is required.',
            'shopby.in' => 'Invalid shop by value.',
            'type.required' => 'Type selection is required.',
            'type.integer' => 'Invalid type selection.',
            'parent_category_id.required' => 'Parent category is required when selecting subcategory.',
            'parent_category_id.exists' => 'Selected parent category does not exist.',
            'url.required' => 'URL is required when redirect type is URL.',
            'url.url' => 'Please enter a valid URL.',
            'imgfile.required' => 'Image is required.',
            'imgfile.image' => 'The file must be an image.',
            'imgfile.mimes' => 'The image must be jpeg, png, jpg, gif, svg, or webp.',
            'imgfile.max' => 'The image size must not exceed 2MB.',
        ]);

        try {
            $imgurl = '';
            $path = $request->file('imgfile');

            if (!empty($path) && $path->isValid()) {
                $storedPath = $path->store('image', 'public');
                $imgurl = 'storage/' . $storedPath;
            }

            $data = new Topcollection;
            $data->name = $request->name;
            $data->description = $request->description;
            $data->name_ar = $request->name_ar;
            $data->description_ar = $request->description_ar;
            $data->imageurl = $imgurl;
            $data->redirect_type = $request->redirect_type;

            if($request->shopby == 'category'){
                $redirect_by = 'categoryId';
            } elseif($request->shopby == 'subcategory'){
                $redirect_by = 'subcategoryId';
            } elseif($request->shopby == 'brand'){
                $redirect_by = 'brandId';
            } elseif($request->shopby == 'product'){
                $redirect_by = 'productId';
            } else {
                $redirect_by = null;
            }

            $data->redirect_by = $redirect_by;
            if ($request->redirect_type == 'URL') {
                $data->shopby = null;
                $data->type = null;
                $data->url = $request->url;
                $data->parent_category_id = null;
            } else {
                $data->shopby = ucfirst($request->shopby);
                $data->type = $request->type;
                $data->url = null;
                
                if ($request->shopby == 'subcategory' && $request->redirect_type == 'Product listing') {
                    $data->parent_category_id = $request->parent_category_id;
                } else {
                    $data->parent_category_id = null;
                }
            }
            
            $data->delete_status = '0';
            $data->created_by = Auth::user()->id;
            $data->save();

            $searchtag = new Searchtag;
            $searchtag->title = $request->name;
            $searchtag->title_ar = $request->name_ar;
            $searchtag->created_by = Auth::user()->id;
            $searchtag->save();

            return redirect('/topcollection')->with('success', 'Top banner created successfully.');
        } catch (\Exception $e) {
            Log::info('Top banner creation failed: ' . $e->getMessage());
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

    public function edit(Topcollection $topcollection, $id)
    {
        $title = "Top Banner";
        $log = Topcollection::where('id', $id)->first();
        
        if (empty($log)) {
            return redirect('/topcollection')->with('error', 'Top banner not found.');
        }
        
        $categories = Category::where('delete_status', '0')->get();
        $subcategories = Subcategory::where('delete_status', '0')->get();
        $brands = Brand::where('delete_status', '0')->get();
        $products = Product::where('delete_status', '0')->get();

        return view('topcollection.edit', compact('title', 'log', 'categories', 'subcategories', 'brands', 'products'));  
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
            'redirect_type' => 'required|string|in:Category,Product listing,Product detail,URL',
            'imgfile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'imgfile_val' => 'nullable|string',
        ];

        if ($request->redirect_type == 'URL') {
            $rules['url'] = 'required|url';
        } else {
            $rules['shopby'] = 'required|string|in:category,subcategory,brand,product';
            $rules['type'] = 'required|integer';
            
            if ($request->shopby == 'subcategory' && $request->redirect_type == 'Product listing') {
                $rules['parent_category_id'] = 'required|integer|exists:categories,id';
            }
        }

        $this->validate($request, $rules, [
            'editid.required' => 'Record ID is required.',
            'editid.exists' => 'Selected record does not exist.',
            'name.required' => 'Name is required.',
            'name.max' => 'Name must not exceed 255 characters.',
            'name_ar.max' => 'Arabic name must not exceed 255 characters.',
            'redirect_type.required' => 'Redirect type is required.',
            'redirect_type.in' => 'Invalid redirect type.',
            'shopby.required' => 'Shop by is required.',
            'shopby.in' => 'Invalid shop by value.',
            'type.required' => 'Type selection is required.',
            'type.integer' => 'Invalid type selection.',
            'parent_category_id.required' => 'Parent category is required when selecting subcategory.',
            'parent_category_id.exists' => 'Selected parent category does not exist.',
            'url.required' => 'URL is required when redirect type is URL.',
            'url.url' => 'Please enter a valid URL.',
            'imgfile.image' => 'The file must be an image.',
            'imgfile.mimes' => 'The image must be jpeg, png, jpg, gif, svg, or webp.',
            'imgfile.max' => 'The image size must not exceed 2MB.',
        ]);

        $data = Topcollection::find($request->editid);

        if (empty($data)) { 
            return redirect('/topcollection')->with('error', 'Top banner not found.');
        }

        try {
            $imgurl = '';
            $path = $request->file('imgfile');

            if (!empty($path) && $path->isValid()) {
                $storedPath = $path->store('image', 'public');
                $imgurl = 'storage/' . $storedPath;
            } else {
                $imgurl = $request->imgfile_val ?? $data->imageurl;
            }

            // Set redirect_by based on shopby
            if ($request->redirect_type == 'URL') {
                $redirect_by = null;
            } else {
                if ($request->shopby == 'category') {
                    $redirect_by = 'categoryId';
                } elseif ($request->shopby == 'subcategory') {
                    $redirect_by = 'subcategoryId';
                } elseif ($request->shopby == 'brand') {
                    $redirect_by = 'brandId';
                } elseif ($request->shopby == 'product') {
                    $redirect_by = 'productId';
                } else {
                    $redirect_by = null;
                }
            }

            $data->name = $request->name;
            $data->description = $request->description;
            $data->name_ar = $request->name_ar;
            $data->description_ar = $request->description_ar;
            $data->imageurl = $imgurl;
            $data->redirect_type = $request->redirect_type;
            $data->redirect_by = $redirect_by;

            if ($request->redirect_type == 'URL') {
                $data->shopby = null;
                $data->type = null;
                $data->url = $request->url;
                $data->parent_category_id = null;
            } else {
                $data->shopby = ucfirst($request->shopby);
                $data->type = $request->type;
                $data->url = null;
                
                if ($request->shopby == 'subcategory' && $request->redirect_type == 'Product listing') {
                    $data->parent_category_id = $request->parent_category_id;
                } else {
                    $data->parent_category_id = null;
                }
            }

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
            Log::info('Top banner deletion failed: ' . $e->getMessage());
            return redirect('/topcollection')->with('error', 'Failed to delete top banner. Please try again.');
        }

    }

}

