<?php



namespace App\Http\Controllers;



use App\Models\Banner;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use Auth;



class BannerController extends Controller

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

        $title = "Banners";

        $indexes = Banner::where('delete_status','0')->get();

        return view('banner.index',compact('title','indexes'));  

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        $title = "Banners";

        return view('banner.create',compact('title')); 

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
            'order_id' => 'nullable|integer|min:0',
            'imgfile' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'imgfile_sm' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $imgurl    = '';

        $imgurl_sm='';

        $path   = $request->file('imgfile');

        $path_sm   = $request->file('imgfile_sm');

        if (!empty($path)) {

            $storedPath  = $path->store('image', 'public');
            $imgurl = 'storage/'.$storedPath;

        }



        if (!empty($path_sm)) {
            $storedPath  = $path_sm->store('image', 'public');
            $imgurl_sm = 'storage/'.$storedPath;
        }

        //echo "hi"; exit();



        $data = new Banner; 

        $data->order_id = $request->order_id;

        $data->imageurl    = $imgurl;

        $data->image_sm    = $imgurl_sm;

        $data->created_by=Auth::user()->id;

        $data->save();

        return redirect('/banner');

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Banner  $banner

     * @return \Illuminate\Http\Response

     */

    public function show(Banner $banner)

    {

        

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Banner  $banner

     * @return \Illuminate\Http\Response

     */

    public function edit(Banner $banner,$id)

    {

        $title = "Banners";

        $log = Banner::where('id',$id)->first();

        return view('banner.edit',compact('title','log'));  

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Banner  $banner

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, Banner $banner)

    {
        $this->validate($request, [
            'editid' => 'required|exists:banners,id',
            'order_id' => 'nullable|integer|min:0',
            'imgfile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'imgfile_sm' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'imgfile_val' => 'nullable|string',
            'imgfile_val_sm' => 'nullable|string',
        ]);

        $imgurl    = '';

        $imgurl_sm    = '';

        $path   = $request->file('imgfile');

        $path_sm   = $request->file('imgfile_sm');

        if (!empty($path)) {
            $storedPath  = $path->store('image', 'public');
            $imgurl = 'storage/'.$storedPath;

        }

        else{

            $imgurl=$request->imgfile_val;

        }



        if (!empty($path_sm)) {
            
            $storedPath  = $path_sm->store('image', 'public');
            $imgurl_sm = 'storage/'.$storedPath;

        }

        else{

            $imgurl_sm=$request->imgfile_val_sm;

        }



        $data = Banner::find($request->editid);

        if (empty($data)) { 
            return redirect('/banner')->with('error', 'Banner not found.');
        }

        $data->order_id = $request->order_id;

        $data->imageurl    = $imgurl;

        $data->image_sm    = $imgurl_sm;

        $data->updated_by=Auth::user()->id;

        $data->save();

        return redirect('/banner');

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Banner  $banner

     * @return \Illuminate\Http\Response

     */

    public function destroy(Banner $banner,$id)

    {

        $data = Banner::find($id);

        $data->delete_status = 1;

        $data->save();

        return redirect('/banner');

    }

}

