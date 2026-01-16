<?php



namespace App\Http\Controllers;



use App\Models\Productvariant;

use App\Models\Variant;

use App\Models\Variantsub;

use App\Models\Cart;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use Auth;



class ProductvariantController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index($id)

    {   

        $title = "Product Variant";

        $indexes = Productvariant::active()->where('product_id',$id)->get();

       

        return view('productvariant.index',compact('title','indexes','id'));

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */
    public function create($id)
    {   
        $title = "Product Variant";
        $product = \App\Models\Product::findOrFail($id);
        
        $size = collect();
        $color = collect();
        if (!empty($product->size)) {
            $sizeIds = explode(',', $product->size);
            $size = Variantsub::active()
                ->whereIn('id', $sizeIds)
                ->get();
        }
        
        if (!empty($product->colors)) {
            $colorIds = explode(',', $product->colors);
            $color = Variantsub::active()
                ->whereIn('id', $colorIds)
                ->get();
        }
        return view('productvariant.create', compact('title', 'id', 'size', 'color'));
    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)

    {
        // Convert "0" to null for color_id and size_id to avoid validation errors
        if($request->color_id == '0' || $request->color_id == '') {
            $request->merge(['color_id' => null]);
        }
        if($request->size_id == '0' || $request->size_id == '') {
            $request->merge(['size_id' => null]);
        }

        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
            'color_id' => 'nullable|exists:variants_sub,id',
            'size_id' => 'required|exists:variants_sub,id',
            'price' => 'required|numeric|min:0',
            'imgfile' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'imgfile2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'imgfile3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ], [
            'product_id.required' => 'Product is required.',
            'product_id.exists' => 'Selected product does not exist.',
            'size_id.required' => 'Size is required. Please select a size.',
            'size_id.exists' => 'Selected size is invalid.',
            'color_id.exists' => 'Selected color is invalid.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price must be at least 0.',
            'imgfile.required' => 'Image file is required. Please upload an image.',
            'imgfile.image' => 'The file must be an image.',
            'imgfile.mimes' => 'Image must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'imgfile.max' => 'Image size must not exceed 2MB.',
            'imgfile2.image' => 'Image file 2 must be an image.',
            'imgfile2.mimes' => 'Image file 2 must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'imgfile2.max' => 'Image file 2 size must not exceed 2MB.',
            'imgfile3.image' => 'Image file 3 must be an image.',
            'imgfile3.mimes' => 'Image file 3 must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'imgfile3.max' => 'Image file 3 size must not exceed 2MB.',
        ]);
        $imgurl = '';
        $path = $request->file('imgfile');

        if (!empty($path)) {
            try {
                $filename = time() . '_' . uniqid() . '.' . $path->getClientOriginalExtension();
                $destinationPath = storage_path('app/public/image');
                
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                
                $path->move($destinationPath, $filename);
                $imgurl = '/storage/image/' . $filename;
                
            } catch (\Exception $e) {
                \Log::error('Image 1 Storage Error: ' . $e->getMessage());
            }
        } else {
            $imgurl = $request->imgfile_val;
        }

        // Image 2
        $imgurl2 = '';
        $path2 = $request->file('imgfile2');

        if (!empty($path2)) {
            try {
                $filename2 = time() . '_' . uniqid() . '.' . $path2->getClientOriginalExtension();
                $destinationPath = storage_path('app/public/image');
                
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                
                $path2->move($destinationPath, $filename2);
                $imgurl2 = '/storage/image/' . $filename2;
                
            } catch (\Exception $e) {
                \Log::error('Image 2 Storage Error: ' . $e->getMessage());
            }
        } else {
            $imgurl2 = $request->imgfile_val2;
        }

        // Image 3
        $imgurl3 = '';
        $path3 = $request->file('imgfile3');

        if (!empty($path3)) {
            try {
                $filename3 = time() . '_' . uniqid() . '.' . $path3->getClientOriginalExtension();
                $destinationPath = storage_path('app/public/image');
                
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                
                $path3->move($destinationPath, $filename3);
                $imgurl3 = '/storage/image/' . $filename3;
                
            } catch (\Exception $e) {
                \Log::error('Image 3 Storage Error: ' . $e->getMessage());
            }
        } else {
            $imgurl3 = $request->imgfile_val3;
        }

        $data = new Productvariant; 

        $data->color_id = $request->color_id;

        $data->size_id = $request->size_id;

        $data->price = $request->price;

        $data->product_id = $request->product_id;

        $data->imageurl    = $imgurl;

        $data->imageurl2    = $imgurl2;

        $data->imageurl3    = $imgurl3;

        $data->created_by=Auth::user()->id;

        $data->save();

        return redirect('/productvariants/'.$request->product_id)->with('success', 'Product variant created successfully.');

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Models\Productvariant  $Productvariant

     * @return \Illuminate\Http\Response

     */

    public function show(Productvariant $Productvariant)

    {

        //

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Models\Productvariant  $Productvariant

     * @return \Illuminate\Http\Response

     */

    // public function edit(Productvariant $Productvariant,$id)

    // {   
    //     $title = "Product Variant";

    //     $log = Productvariant::with(['product', 'sizeVariant', 'colorVariant'])
    //         ->where('id',$id)
    //         ->first();

    //     if (empty($log)) {
    //         return redirect()->back()->with('error', 'Product variant not found.');
    //     }

    //     // Dynamic variant lookup instead of hardcoded IDs
    //     $sizeVariant = \App\Models\Variant::where('name', 'Size')->active()->first();
    //     $colorVariant = \App\Models\Variant::where('name', 'Color')->active()->first();
        
    //     $size = collect();
    //     $color = collect();
        
    //     if ($sizeVariant) {
    //         $size = Variantsub::active()->where('variant_id', $sizeVariant->id)->get();
    //     }
        
    //     if ($colorVariant) {
    //         $color = Variantsub::active()->where('variant_id', $colorVariant->id)->get();
    //     }

    //     return view('productvariant.edit', compact('title', 'log', 'size', 'color'));

    // }
    public function edit(Productvariant $Productvariant, $id)
    {   
        $title = "Product Variant";

        $log = Productvariant::with(['product', 'sizeVariant', 'colorVariant'])
            ->where('id', $id)
            ->first();

        if (empty($log)) {
            return redirect()->back()->with('error', 'Product variant not found.');
        }

        $product = $log->product;
        
        $size = collect();
        $color = collect();
        
        if (!empty($product->size)) {
            $sizeIds = explode(',', $product->size);
            $size = Variantsub::active()
                ->whereIn('id', $sizeIds)
                ->get();
        }
        
        if (!empty($product->colors)) {
            $colorIds = explode(',', $product->colors);
            $color = Variantsub::active()
                ->whereIn('id', $colorIds)
                ->get();
        }

        return view('productvariant.edit', compact('title', 'log', 'size', 'color'));
    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Models\Productvariant  $Productvariant

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, Productvariant $Productvariant)

    {
        // Convert "0" to null for color_id and size_id to avoid validation errors
        if($request->color_id == '0' || $request->color_id == '') {
            $request->merge(['color_id' => null]);
        }
        if($request->size_id == '0' || $request->size_id == '') {
            $request->merge(['size_id' => null]);
        }

        $this->validate($request, [
            'editid' => 'required|exists:productvariants,id',
            'product_id' => 'required|exists:products,id',
            'color_id' => 'nullable|exists:variants_sub,id',
            'size_id' => 'required|exists:variants_sub,id',
            'price' => 'required|numeric|min:0',
            'imgfile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'imgfile2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'imgfile3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'imgfile_val' => 'nullable|string',
            'imgfile_val2' => 'nullable|string',
            'imgfile_val3' => 'nullable|string',
        ], [
            'editid.required' => 'Variant ID is required.',
            'editid.exists' => 'Selected variant does not exist.',
            'product_id.required' => 'Product is required.',
            'product_id.exists' => 'Selected product does not exist.',
            'size_id.required' => 'Size is required. Please select a size.',
            'size_id.exists' => 'Selected size is invalid.',
            'color_id.exists' => 'Selected color is invalid.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price must be at least 0.',
            'imgfile.image' => 'The file must be an image.',
            'imgfile.mimes' => 'Image must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'imgfile.max' => 'Image size must not exceed 2MB.',
            'imgfile2.image' => 'Image file 2 must be an image.',
            'imgfile2.mimes' => 'Image file 2 must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'imgfile2.max' => 'Image file 2 size must not exceed 2MB.',
            'imgfile3.image' => 'Image file 3 must be an image.',
            'imgfile3.mimes' => 'Image file 3 must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'imgfile3.max' => 'Image file 3 size must not exceed 2MB.',
        ]);
            // Image 1
        $imgurl = '';
        $path = $request->file('imgfile');

        if (!empty($path)) {
            try {
                $filename = time() . '_' . uniqid() . '.' . $path->getClientOriginalExtension();
                $destinationPath = storage_path('app/public/image');
                
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                
                $path->move($destinationPath, $filename);
                $imgurl = '/storage/image/' . $filename;
                
            } catch (\Exception $e) {
                \Log::error('Image 1 Storage Error: ' . $e->getMessage());
            }
        } else {
            $imgurl = $request->imgfile_val;
        }

        // Image 2
        $imgurl2 = '';
        $path2 = $request->file('imgfile2');

        if (!empty($path2)) {
            try {
                $filename2 = time() . '_' . uniqid() . '.' . $path2->getClientOriginalExtension();
                $destinationPath = storage_path('app/public/image');
                
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                
                $path2->move($destinationPath, $filename2);
                $imgurl2 = '/storage/image/' . $filename2;
                
            } catch (\Exception $e) {
                \Log::error('Image 2 Storage Error: ' . $e->getMessage());
            }
        } else {
            $imgurl2 = $request->imgfile_val2;
        }

        // Image 3
        $imgurl3 = '';
        $path3 = $request->file('imgfile3');

        if (!empty($path3)) {
            try {
                $filename3 = time() . '_' . uniqid() . '.' . $path3->getClientOriginalExtension();
                $destinationPath = storage_path('app/public/image');
                
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                
                $path3->move($destinationPath, $filename3);
                $imgurl3 = '/storage/image/' . $filename3;
                
            } catch (\Exception $e) {
                \Log::error('Image 3 Storage Error: ' . $e->getMessage());
            }
        } else {
            $imgurl3 = $request->imgfile_val3;
        }
        $data = Productvariant::find($request->editid);

        if (empty($data)) { 
            return redirect('/productvariants/'.$request->product_id)->with('error', 'Product variant not found.');
        }

        

        $data->color_id = $request->color_id;

        $data->size_id = $request->size_id;

        $data->price = $request->price;

        $data->product_id = $request->product_id;

        $data->imageurl    = $imgurl;
        $data->imageurl2    = $imgurl2;
        $data->imageurl3    = $imgurl3;

        $data->created_by=Auth::user()->id;

        $data->save();

        $this->updatecartprices($request->product_id,$request->editid,$request->price);
        
         return redirect('/productvariants/'.$request->product_id)->with('success', 'Product variant updated successfully.');

    }


    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Models\Productvariant  $Productvariant

     * @return \Illuminate\Http\Response

     */

    public function destroy(Productvariant $Productvariant,$id,$product_id)

    {

        $data = Productvariant::find($id);

        if (empty($data)) {
            return redirect('/productvariants/'.$product_id)->with('error', 'Product variant not found.');
        }

        $data->delete_status = 1;

        $data->save();

        return redirect('/productvariants/'.$product_id)->with('success', 'Product variant deleted successfully.');

    }

    public function updatecartprices($product_id='',$variant_id,$actualprice)
    {
        $carts =  Cart::where('product_id',$product_id)->where('variant_id',$variant_id)->where('carted','0')->where('delete_status',0)->get();
        foreach ($carts as $cart) {
            $totalprice = $actualprice*$cart->qty;
            Cart::where('id',$cart->id)->update(['actual_price'=>$actualprice,'total_price'=>$totalprice]);
        }
    }

}

