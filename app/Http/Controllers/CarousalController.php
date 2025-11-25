<?php



namespace App\Http\Controllers;



use App\Models\Category;

use App\Models\Subcategory;

use App\Models\Brand;

use App\Models\Carousal;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use Auth;



class CarousalController extends Controller

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

        $title = "Carousals";

        $indexes = Carousal::where('delete_status','0')->get();

        return view('carousal.index',compact('title','indexes'));  

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        $title = "Carousals";

        return view('carousal.create',compact('title')); 

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
            'imgfile_sm' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $imgurl    = '';

        $path   = $request->file('imgfile');

        if (!empty($path)) {

        $store  = Storage::putFile('public/image', $path);

            //$imgurl    = Storage::url($store);

            //$imgurl = url('/').'/storage/app/'.$store;

            $imgurl = config('app.imgurl').basename($store);

        }



        $data = new Carousal; 

        $data->name = $request->name;

        $data->description = $request->description;

        $data->name_ar = $request->name_ar;

        $data->description_ar = $request->description_ar;

        $data->imageurl    = $imgurl;

        $data->created_by=Auth::user()->id;

        $data->save();

        return redirect('/carousal');

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Carousal  $carousal

     * @return \Illuminate\Http\Response

     */

    public function show(Carousal $carousal)

    {

        

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Carousal  $carousal

     * @return \Illuminate\Http\Response

     */

    public function edit(Carousal $carousal,$id)

    {

        $title = "Carousals";

        $log = Carousal::where('id',$id)->first();

        return view('carousal.edit',compact('title','log'));  

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Carousal  $carousal

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, Carousal $carousal)

    {
        $this->validate($request, [
            'editid' => 'required|exists:carousals,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'imgfile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'imgfile_val' => 'nullable|string',
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



        $data = Carousal::find($request->editid);

        if (empty($data)) { 
            return redirect('/carousal')->with('error', 'Carousal not found.');
        }

        $data->name = $request->name;

        $data->description = $request->description;

        $data->name_ar = $request->name_ar;

        $data->description_ar = $request->description_ar;

        $data->imageurl    = $imgurl;

        $data->updated_by=Auth::user()->id;

        $data->save();

        return redirect('/carousal');

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Carousal  $subCarousal

     * @return \Illuminate\Http\Response

     */

    public function destroy(Carousal $carousal,$id)

    {

        $data = Carousal::find($id);

        $data->delete_status = 1;

        $data->save();

        return redirect('/carousal');

    }

}

