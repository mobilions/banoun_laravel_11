<?php



namespace App\Http\Controllers;



use App\Models\Delivery;

use App\Models\Searchtag;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use Auth;



class DeliveryController extends Controller

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

        $title = "Delivery Info";

        $indexes = Delivery::active()->get();

        return view('delivery.index',compact('title','indexes'));  

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        $title = "Delivery Info";

        return view('delivery.create',compact('title')); 

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
            'imgfile' => 'nullable|image|mimes:svg',
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



        $data = new Delivery; 

        $data->name = $request->name;

        $data->name_ar = $request->name_ar;

        $data->imageurl    = $imgurl;

        $data->created_by=Auth::user()->id;

        $data->save();



        return redirect('/delivery');

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Brand  $delivery

     * @return \Illuminate\Http\Response

     */

    public function show(Delivery $delivery)

    {

        

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Brand  $delivery

     * @return \Illuminate\Http\Response

     */

    public function edit(Delivery $delivery,$id)

    {

        $title = "Delivery";

        $log = Delivery::where('id',$id)->first();

        return view('delivery.edit',compact('title','log'));  

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Brand  $delivery

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, Delivery $delivery)

    {
        $this->validate($request, [
            'editid' => 'required|exists:deliveries,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'imgfile' => 'nullable|image|mimes:svg',
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



        $data = Delivery::find($request->editid);

        if (empty($data)) { 
            return redirect('/delivery')->with('error', 'Delivery record not found.');
        }

        $data->name = $request->name;

        $data->name_ar = $request->name_ar;

        $data->imageurl    = $imgurl;

        $data->updated_by=Auth::user()->id;

        $data->save();

        return redirect('/delivery');

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Brand  $delivery

     * @return \Illuminate\Http\Response

     */

    public function destroy(Delivery $delivery,$id)

    {

        $data = Delivery::find($id);

        $data->delete_status = 1;

        $data->save();

        return redirect('/delivery');

    }

}

