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
            ->where('variants.delete_status', 0)
            ->where('variants_sub.delete_status', 0)
            ->addSelect('variants.name as variant','variants.name_ar as variant_ar','variants_sub.*')
            ->orderByDesc('variants_sub.created_at')
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

        // Get variant to check its name
        $variant = Variant::find($request->variant_id);
        $variantName = $variant ? strtolower($variant->name) : '';
        
        // If variant is "Color", use color_val, otherwise use age field
        if($variantName === 'color'){

            $color_val=$request->color_val;

        }

        else{

            $color_val=$request->age ?: '';

        }

        $data = new Variantsub; 

        $data->name = $request->name;

        $data->variant_id = $request->variant_id;

        $data->name_ar = $request->name_ar;

        $data->color_val = $color_val;

        $data->created_by=Auth::user()->id;

        $data->save();

        return redirect('/variantsub')->with('success', 'Variant value created successfully.');

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

        // Get variant to check its name
        $variant = Variant::find($request->variant_id);
        $variantName = $variant ? strtolower($variant->name) : '';
        
        // If variant is "Color", use color_val, otherwise use age field
        if($variantName === 'color'){

            $color_val=$request->color_val;

        }

        else{

            $color_val=$request->age ?: '';

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

        return redirect('/variantsub')->with('success', 'Variant value updated successfully.');

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

        if (empty($data)) {
            return redirect('/variantsub')->with('error', 'Variant value not found.');
        }

        $data->delete_status = 1;

        $data->save();

        return redirect('/variantsub')->with('success', 'Variant value deleted successfully.');

    }

    public function addVariantValue($variant_id='', $name='', $name_ar='', $color_val='')
    {
        if(empty($variant_id) || empty($name)) {
            return response()->json(['error' => 'Variant ID and Name are required.'], 400);
        }

        $data = new Variantsub; 
        $data->name = $name;
        $data->variant_id = $variant_id;
        $data->name_ar = $name_ar ?: $name;
        $data->color_val = $color_val ?: '';
        $data->created_by = Auth::user()->id;
        $data->save();

        return response()->json(['id' => $data->id, 'name' => $data->name, 'name_ar' => $data->name_ar]);
    }

}

