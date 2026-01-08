<?php



namespace App\Http\Controllers;



use App\Models\Category;

use App\Models\Subcategory;

use App\Models\Searchtag;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use Auth;



class SubcategoryController extends Controller

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

        $title = "SubCategory";

        $indexes = Subcategory::join('categories', 'subcategories.category_id', '=', 'categories.id')
            ->where('categories.delete_status', 0)
            ->where('subcategories.delete_status', 0)
            ->addSelect('categories.name as category','categories.name_ar as category_ar','subcategories.*')
            ->orderByDesc('subcategories.created_at')
            ->get();

        return view('subcategory.index',compact('title','indexes'));  

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        $title = "SubCategory";

        $lists = Category::active()->get();

        return view('subcategory.create',compact('title','lists')); 

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
            'category_id' => 'required|array|min:1',
            'category_id.*' => 'exists:categories,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'imgfile' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'imgfile.required' => 'Image file is required.',
            'imgfile.image' => 'Image file must be an image.',
            'imgfile.mimes' => 'Image file must be a file of type: jpeg, png, jpg, gif, svg.',
            'imgfile.max' => 'Image file size must not exceed 2MB.',
        ]);

        $category_id=$request->category_id;

        $imgurl    = '';

        $path   = $request->file('imgfile');

        if ($path) {
            $storedPath = $path->store('image', 'public');
            $imgurl = 'storage/'.$storedPath;
        }

        //echo "hi"; exit();

        foreach ($category_id as $category) {

            $data = new Subcategory; 

            $data->name = $request->name;

            $data->description = $request->description;

            $data->name_ar = $request->name_ar;

            $data->description_ar = $request->description_ar;

            $data->imageurl    = $imgurl;

            $data->category_id = $category;

            $data->created_by=Auth::user()->id;

            $data->save();



            $data = new Searchtag; 

            $data->title = $request->name;

            $data->title_ar = $request->name_ar;

            $data->created_by=Auth::user()->id;

            $data->save();

        }

        return redirect('/subcategory')->with('success', 'Subcategory created successfully.');

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Models\Subcategory  $subcategory

     * @return \Illuminate\Http\Response

     */

    public function show(Subcategory $subcategory)

    {

        

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Models\Subcategory  $subcategory

     * @return \Illuminate\Http\Response

     */

    public function edit(Subcategory $subcategory,$id)

    {

        $title = "SubCategory";

        $log = Subcategory::where('id',$id)->first();

        $lists = Category::active()->get();

        return view('subcategory.edit',compact('title','log','lists'));  

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Models\Subcategory  $subcategory

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, Subcategory $subcategory)

    {
        $this->validate($request, [
            'editid' => 'required|exists:subcategories,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'imgfile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'imgfile_val' => 'nullable|string',
        ], [
            'imgfile.image' => 'Image file must be an image.',
            'imgfile.mimes' => 'Image file must be a file of type: jpeg, png, jpg, gif, svg.',
            'imgfile.max' => 'Image file size must not exceed 2MB.',
        ]);

        $imgurl    = '';

        $path   = $request->file('imgfile');

        if ($path) {
            $storedPath = $path->store('image', 'public');
            $imgurl = 'storage/'.$storedPath;
        } else {
            $imgurl = $request->imgfile_val;
        }



        $data = Subcategory::find($request->editid);

        if (empty($data)) { 
            return redirect('/subcategory')->with('error', 'Subcategory not found.');
        }

        $data->name = $request->name;

        $data->description = $request->description;

        $data->name_ar = $request->name_ar;

        $data->description_ar = $request->description_ar;

        $data->imageurl    = $imgurl;

        $data->category_id = $request->category_id;

        $data->updated_by=Auth::user()->id;

        $data->save();

        return redirect('/subcategory')->with('success', 'Subcategory updated successfully.');

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Models\Subcategory  $subSubcategory

     * @return \Illuminate\Http\Response

     */

    public function destroy(Subcategory $subcategory,$id)

    {

        $data = Subcategory::find($id);

        if (empty($data)) {
            return redirect('/subcategory')->with('error', 'Subcategory not found.');
        }

        $data->delete_status = 1;

        $data->save();

        return redirect('/subcategory')->with('success', 'Subcategory deleted successfully.');

    }

}

