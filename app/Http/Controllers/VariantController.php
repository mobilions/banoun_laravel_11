<?php



namespace App\Http\Controllers;



use App\Models\Variant;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use Auth;



class VariantController extends Controller

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

        $title = "variants";

        $indexes = Variant::where('delete_status','0')->get();

        return view('variant.index',compact('title','indexes'));  

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        $title = "variants";

        return view('variant.create',compact('title')); 

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
        ]);

        $data = new Variant; 

        $data->name = $request->name;

        $data->description = $request->description;

        $data->name_ar = $request->name_ar;

        $data->description_ar = $request->description_ar;

        $data->created_by=Auth::user()->id;

        $data->save();

        return redirect('/variant');

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Models\Variant  $variant

     * @return \Illuminate\Http\Response

     */

    public function show(Variant $variant)

    {

        

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Models\Variant  $variant

     * @return \Illuminate\Http\Response

     */

    public function edit(Variant $variant,$id)

    {

        $title = "Variant";

        $log = Variant::where('id',$id)->first();

        return view('variant.edit',compact('title','log'));  

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Models\Variant  $variant

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, Variant $variant)

    {
        $this->validate($request, [
            'editid' => 'required|exists:variants,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
        ]);

        $data = Variant::find($request->editid);

        if (empty($data)) { 
            return redirect('/variant')->with('error', 'Variant not found.');
        }

        $data->name = $request->name;

        $data->description = $request->description;

        $data->name_ar = $request->name_ar;

        $data->description_ar = $request->description_ar;

        $data->updated_by=Auth::user()->id;

        $data->save();

        return redirect('/variant');

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Models\Variant  $variant

     * @return \Illuminate\Http\Response

     */

    public function destroy(Variant $variant,$id)

    {

        $data = Variant::find($id);

        $data->delete_status = 1;

        $data->save();

        return redirect('/variant');

    }

}

