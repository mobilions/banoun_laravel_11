<?php



namespace App\Http\Controllers;



use App\Models\Category;

use App\Models\Subcategory;

use App\Models\Brand;

use App\Models\Product;

use App\Models\usbanner;

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

        $indexes = usbanner::where('delete_status','0')->get();

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
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'grid' => 'nullable|string|max:50',
            'shopby' => 'nullable|string|in:category,subcategory,brand,product',
            'redirect_type' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'imgfile' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'imgfile_sm' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $imgurl_sm='';

        $path_sm   = $request->file('imgfile_sm');

        if (!empty($path_sm)) {

            $store  = Storage::putFile('public/image', $path_sm);

            //$imgurl    = Storage::url($store);

            //$imgurl = url('/').'/storage/app/'.$store;

            $imgurl_sm = config('app.imgurl').basename($store);

        }

        $imgurl    = '';

        $path   = $request->file('imgfile');

        if (!empty($path)) {

        $store  = Storage::putFile('public/image', $path);

            //$imgurl    = Storage::url($store);

            //$imgurl = url('/').'/storage/app/'.$store;

            $imgurl = config('app.imgurl').basename($store);

        }

        //echo "hi"; exit();



        $redirect_type=$request->redirect_type;

        if($redirect_type=='productlist'){

            if($request->shopby=='category'){

                $redirect_by='categoryId';

            }

            if($request->shopby=='subcategory'){

                $redirect_by='subcategoryId';

            }

            if($request->shopby=='brand'){

                $redirect_by='brandId';

            }

            if($request->shopby=='product'){

                $redirect_by='productId';

            }

            

        }

        else{

            $redirect_by=DB::table('banner_url')->where('base_url',$redirect_type)->first();

            $redirect_by=$redirect_by->base_id;

        }



        $data = new usbanner; 

        $data->name = $request->name;

        $data->description = $request->description;

        $data->name_ar = $request->name_ar;

        $data->description_ar = $request->description_ar;

        $data->grid=$request->grid;

        $data->imageurl    = $imgurl;

        $data->image_sm    = $imgurl_sm;

        $data->shopby = $request->shopby;

        $data->redirect_type = $redirect_type;

        $data->redirect_by = $redirect_by;

        $data->category_id = $request->category_id;

        $data->created_by=Auth::user()->id;

        $data->save();

        return redirect('/usbanner');

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\usbanner  $usbanner

     * @return \Illuminate\Http\Response

     */

    public function show(usbanner $usbanner)

    {

        

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\usbanner  $usbanner

     * @return \Illuminate\Http\Response

     */

    public function edit(usbanner $usbanner,$id)

    {

        $title = "Promotion Banner";

        $log = usbanner::where('id',$id)->first();

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

    public function update(Request $request, usbanner $usbanner)

    {
        $this->validate($request, [
            'editid' => 'required|exists:usbanners,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'grid' => 'nullable|string|max:50',
            'shopby' => 'nullable|string|in:category,subcategory,brand,product',
            'redirect_type' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'imgfile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'imgfile_sm' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'imgfile_val' => 'nullable|string',
            'imgfile_val_sm' => 'nullable|string',
        ]);

        $imgurl_sm    = '';

        $path_sm   = $request->file('imgfile_sm');

        if (!empty($path_sm)) {

            $store  = Storage::putFile('public/image', $path_sm);

            //$imgurl    = Storage::url($store);

            //$imgurl = url('/').'/storage/app/'.$store;

            $imgurl_sm = config('app.imgurl').basename($store);

        }

        else{

            $imgurl_sm=$request->imgfile_val_sm;

        }



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



        $redirect_type=$request->redirect_type;

        if($redirect_type=='productlist'){

            if($request->shopby=='category'){

                $redirect_by='categoryId';

            }

            if($request->shopby=='subcategory'){

                $redirect_by='subcategoryId';

            }

            if($request->shopby=='brand'){

                $redirect_by='brandId';

            }

            if($request->shopby=='product'){

                $redirect_by='productId';

            }

            

        }

        else{

            $redirect_by=DB::table('banner_url')->where('base_url',$redirect_type)->first();

            $redirect_by=$redirect_by->base_id;

        }



        $data = usbanner::find($request->editid);

        if (empty($data)) { 
            return redirect('/usbanner')->with('error', 'Promotion banner not found.');
        }

        $data->name = $request->name;

        $data->description = $request->description;

        $data->name_ar = $request->name_ar;

        $data->description_ar = $request->description_ar;

        $data->grid=$request->grid;

        $data->imageurl    = $imgurl;

        $data->image_sm    = $imgurl_sm;

        $data->shopby = $request->shopby;

        $data->redirect_type = $redirect_type;

        $data->redirect_by = $redirect_by;

        $data->category_id = $request->category_id;

        $data->updated_by=Auth::user()->id;

        $data->save();

        return redirect('/usbanner');

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\usbanner  $subusbanner

     * @return \Illuminate\Http\Response

     */

    public function destroy(usbanner $usbanner,$id)

    {

        $data = usbanner::find($id);

        $data->delete_status = 1;

        $data->save();

        return redirect('/usbanner');

    }

}

