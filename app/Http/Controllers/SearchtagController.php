<?php



namespace App\Http\Controllers;



use App\Models\Searchtag;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Subcategory;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use Auth;



class SearchtagController extends Controller

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

        $title = "Search Tags";

        $indexes = Searchtag::active()->get();

        return view('searchtag.index',compact('title','indexes'));  

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        $title = "Search Tags";

        return view('searchtag.create',compact('title')); 

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
            'title' => 'required|string|max:255',
            'title_ar' => 'nullable|string|max:255',
        ]);

        $data = new Searchtag; 

        $data->title = $request->title;

        $data->title_ar = $request->title_ar;

        $data->created_by=Auth::user()->id;

        $data->save();

        return redirect('/searchtag');

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Models\Searchtag  $searchtag

     * @return \Illuminate\Http\Response

     */

    public function show(Searchtag $searchtag)

    {

        

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Models\Searchtag  $searchtag

     * @return \Illuminate\Http\Response

     */

    public function edit(Searchtag $searchtag,$id)

    {

        $title = "Search Tag";

        $log = Searchtag::where('id',$id)->first();

        return view('searchtag.edit',compact('title','log'));  

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Models\Searchtag  $searchtag

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, Searchtag $searchtag)

    {
        $this->validate($request, [
            'editid' => 'required|exists:searchtags,id',
            'title' => 'required|string|max:255',
            'title_ar' => 'nullable|string|max:255',
        ]);

        $data = Searchtag::find($request->editid);

        if (empty($data)) { 
            return redirect('/searchtag')->with('error', 'Search tag not found.');
        }

        $data->title = $request->title;

        $data->title_ar = $request->title_ar;

        $data->updated_by=Auth::user()->id;

        $data->save();

        return redirect('/searchtag');

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Models\Searchtag  $searchtag

     * @return \Illuminate\Http\Response

     */

    public function destroy(Searchtag $searchtag,$id)

    {

        $data = Searchtag::find($id);

        $data->delete_status = 1;

        $data->save();

        return redirect('/searchtag');

    }

    public function addsearchTag($name='',$name_ar='')
    {

        $id=Searchtag::insertGetId(['title'=>$name,'title_ar'=>$name_ar,'created_by'=>Auth::user()->id]);

        return response()->json(['id'=>$id]);
    }

    public function selectBrandTag($brand_id='')
    {
        $brand=Brand::find($brand_id);
        $searchtag=Searchtag::where('title',$brand->name)->first();
        $id='0';
        if($searchtag){
            $id=$searchtag->id;
        }
        return response()->json(['id'=>$id]);
    }

    

}

