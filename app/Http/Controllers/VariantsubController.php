<?php



namespace App\Http\Controllers;



use App\Models\Variant;

use App\Models\Variantsub;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use Auth;



class VariantsubController extends Controller

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

        $title = "variant";

        $indexes = Variantsub::join('variants', 'variants_sub.variant_id', '=', 'variants.id')
            ->addSelect('variants.name as variant','variants.name_ar as variant_ar','variants_sub.*')
            ->active()
            ->get();

        

        return view('variantsub.index',compact('title','indexes'));  

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        $title = "variant";

        $variants = Variant::active()->get();

        return view('variantsub.create',compact('title','variants')); 

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
            'variant_id' => 'required|exists:variants,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'age' => 'nullable|string|max:50',
            'color_val' => 'nullable|string|max:50',
        ]);

        if($request->variant_id=='1'){

            $color_val=$request->age;

        }

        else{

            $color_val=$request->color_val;

        }

        

        $data = new Variantsub; 

        $data->name = $request->name;

        $data->variant_id = $request->variant_id;

        $data->name_ar = $request->name_ar;

        $data->color_val = $color_val;

        $data->created_by=Auth::user()->id;

        $data->save();

        return redirect('/variantsub');

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Models\Variant  $variantsub

     * @return \Illuminate\Http\Response

     */

    public function show(Variantsub $variantsub)

    {

        

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Models\Variant  $variantsub

     * @return \Illuminate\Http\Response

     */

    public function edit(Variantsub $variantsub,$id)

    {

        $title = "Variant";

        $log = Variantsub::where('id',$id)->first();

        $variants = Variant::active()->get();

        return view('variantsub.edit',compact('title','log','variants'));  

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Models\Variant  $variantsub

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, Variant $variantsub)

    {
        $this->validate($request, [
            'editid' => 'required|exists:variants_sub,id',
            'variant_id' => 'required|exists:variants,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'age' => 'nullable|string|max:50',
            'color_val' => 'nullable|string|max:50',
        ]);

        if($request->variant_id=='1'){

            $color_val=$request->age;

        }

        else{

            $color_val=$request->color_val;

        }

        

        $data = Variantsub::find($request->editid);

        if (empty($data)) { 
            return redirect('/variantsub')->with('error', 'Variant sub not found.');
        }

        $data->name = $request->name;

        $data->variant_id = $request->variant_id;

        $data->name_ar = $request->name_ar;

        $data->color_val = $color_val;

        $data->updated_by=Auth::user()->id;

        $data->save();

        return redirect('/variantsub');

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Models\Variant  $variantsub

     * @return \Illuminate\Http\Response

     */

    public function destroy(Variantsub $variantsub,$id)

    {

        $data = Variantsub::find($id);

        $data->delete_status = 1;

        $data->save();

        return redirect('/variantsub');

    }

}

