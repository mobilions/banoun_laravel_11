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
            'imgfile' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048|dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000',
            'imgfile_sm' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ], [
            'name.required' => 'Title is required.',
            'name.max' => 'Title must not exceed 255 characters.',
            'name_ar.max' => 'Arabic title must not exceed 255 characters.',
            'imgfile.required' => 'Image is required.',
            'imgfile.image' => 'The file must be an image.',
            'imgfile.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'imgfile.max' => 'The image size must not exceed 2MB.',
            'imgfile.dimensions' => 'The image dimensions must be between 100x100 and 4000x4000 pixels.',
        ]);

        try {
            $imgurl = '';

            $path = $request->file('imgfile');

            if (!empty($path) && $path->isValid()) {
                $storedPath = $path->store('image', 'public');
                $imgurl = 'storage/'.$storedPath;
            }

            $data = new Carousal; 
            $data->name = $request->name;
            $data->description = $request->description;
            $data->name_ar = $request->name_ar;
            $data->description_ar = $request->description_ar;
            $data->imageurl = $imgurl;
            $data->delete_status = '0';
            $data->created_by = Auth::user()->id;
            $data->save();

            return redirect('/carousal')->with('success', 'Carousal created successfully.');
        } catch (\Exception $e) {
            \Log::error('Carousal creation failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create carousal. Please try again.');
        }

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
            'imgfile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048|dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000',
            'imgfile_val' => 'nullable|string',
        ], [
            'editid.required' => 'Record ID is required.',
            'editid.exists' => 'Selected record does not exist.',
            'name.required' => 'Title is required.',
            'name.max' => 'Title must not exceed 255 characters.',
            'name_ar.max' => 'Arabic title must not exceed 255 characters.',
            'imgfile.image' => 'The file must be an image.',
            'imgfile.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'imgfile.max' => 'The image size must not exceed 2MB.',
            'imgfile.dimensions' => 'The image dimensions must be between 100x100 and 4000x4000 pixels.',
        ]);

        $data = Carousal::find($request->editid);

        if (empty($data)) { 
            return redirect('/carousal')->with('error', 'Carousal not found.');
        }

        try {
            $imgurl = '';

            $path = $request->file('imgfile');

            if (!empty($path) && $path->isValid()) {
                $storedPath = $path->store('image', 'public');
                $imgurl = 'storage/'.$storedPath;
            } else {
                $imgurl = $request->imgfile_val ?? '';
            }

            $data->name = $request->name;
            $data->description = $request->description;
            $data->name_ar = $request->name_ar;
            $data->description_ar = $request->description_ar;
            $data->imageurl = $imgurl;
            $data->updated_by = Auth::user()->id;
            $data->save();

            return redirect('/carousal')->with('success', 'Carousal updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Carousal update failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update carousal. Please try again.');
        }

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

        if (empty($data)) {
            return redirect('/carousal')->with('error', 'Carousal not found.');
        }

        try {
            $data->delete_status = 1;
            $data->save();

            return redirect('/carousal')->with('success', 'Carousal deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Carousal deletion failed: ' . $e->getMessage());
            return redirect('/carousal')->with('error', 'Failed to delete carousal. Please try again.');
        }

    }

}

