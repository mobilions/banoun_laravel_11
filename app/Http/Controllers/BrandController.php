<?php



namespace App\Http\Controllers;



use App\Models\Brand;

use App\Models\Searchtag;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use Auth;



class BrandController extends Controller

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

        $title = "Brands";

        $indexes = Brand::active()->get();

        return view('brand.index',compact('title','indexes'));  

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        $title = "Brands";

        return view('brand.create',compact('title')); 

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
            'imgfile' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $imgurl    = '';

        $path   = $request->file('imgfile');

        if ($path) {
            $storedPath  = $path->store('image', 'public');
            $imgurl = 'storage/'.$storedPath;
        }

        //echo "hi"; exit();



        $data = new Brand; 

        $data->name = $request->name;

        $data->description = $request->description;

        $data->name_ar = $request->name_ar;

        $data->description_ar = $request->description_ar;

        $data->imageurl    = $imgurl;

        $data->created_by=Auth::user()->id;

        $data->save();



        $data = new Searchtag; 

        $data->title = $request->name;

        $data->title_ar = $request->name_ar;

        $data->created_by=Auth::user()->id;

        $data->save();



        return redirect('/brand');

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Brand  $brand

     * @return \Illuminate\Http\Response

     */

    public function show(Brand $brand)

    {

        

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Brand  $brand

     * @return \Illuminate\Http\Response

     */

    public function edit(Brand $brand,$id)

    {

        $title = "Brand";

        $log = Brand::where('id',$id)->first();

        return view('brand.edit',compact('title','log'));  

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Brand  $brand

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, Brand $brand)

    {
        $this->validate($request, [
            'editid' => 'required|exists:brands,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'imgfile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'imgfile_val' => 'nullable|string',
        ]);

        $imgurl    = '';

        $path   = $request->file('imgfile');

        if ($path) {
            $storedPath  = $path->store('image', 'public');
            $imgurl = 'storage/'.$storedPath;
        } else{
            $imgurl=$request->imgfile_val;
        }



        $data = Brand::find($request->editid);

        if (empty($data)) { 
            return redirect('/brand')->with('error', 'Brand not found.');
        }

        $data->name = $request->name;

        $data->description = $request->description;

        $data->name_ar = $request->name_ar;

        $data->description_ar = $request->description_ar;

        $data->imageurl    = $imgurl;

        $data->updated_by=Auth::user()->id;

        $data->save();

        return redirect('/brand');

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Brand  $brand

     * @return \Illuminate\Http\Response

     */

    public function destroy(Brand $brand,$id)

    {

        $data = Brand::find($id);

        $data->delete_status = 1;

        $data->save();

        return redirect('/brand');

    }

}

