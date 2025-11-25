<?php



namespace App\Http\Controllers;



use App\Models\Category;

use App\Models\Searchtag;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use Auth;



class CategoryController extends Controller

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

        $title = "Categories";

        $indexes = Category::active()->get();

        return view('category.index',compact('title','indexes'));  

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        $title = "Category";

        return view('category.create',compact('title')); 

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
        ]);

        $imgurl    = '';

        $path   = $request->file('imgfile');

        if ($path) {
            $storedPath = $path->store('image', 'public');
            $imgurl = 'storage/'.$storedPath;
        }

        $data = new Category; 

        $data->name = $request->name;

        $data->description = $request->description;

        $data->name_ar = $request->name_ar;

        $data->description_ar = $request->description_ar;

        $data->imageurl    = $imgurl;

        $data->created_by=Auth::user()->id;

        $data->save();



        $data = new Searchtag; 

        $data->title = $request->name;

        $data->title_ar = $request->name_ar;

        $data->created_by=Auth::user()->id;

        $data->save();



        return redirect('/category');

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Category  $category

     * @return \Illuminate\Http\Response

     */

    public function show(Category $category)

    {

        

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Category  $category

     * @return \Illuminate\Http\Response

     */

    public function edit(Category $category,$id)

    {

        $title = "Category";

        $log = Category::where('id',$id)->first();

        return view('category.edit',compact('title','log'));  

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Category  $category

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, Category $category)

    {
        $this->validate($request, [
            'editid' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'imgfile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'imgfile_val' => 'nullable|string',
        ]);

        $imgurl    = '';

        $path   = $request->file('imgfile');



        if ($path) {
            $storedPath = $path->store('image', 'public');
            $imgurl = 'storage/'.$storedPath;
        } else {
            $imgurl = $request->imgfile_val;
        }

        $data = Category::find($request->editid);

        if (empty($data)) { 
            return redirect('/category')->with('error', 'Category not found.');
        }

        $data->name = $request->name;

        $data->description = $request->description;

        $data->name_ar = $request->name_ar;

        $data->description_ar = $request->description_ar;

        $data->imageurl    = $imgurl;

        $data->updated_by=Auth::user()->id;

        $data->save();

        return redirect('/category');

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Category  $category

     * @return \Illuminate\Http\Response

     */

    public function destroy(Category $category,$id)

    {

        $data = Category::find($id);

        $data->delete_status = 1;

        $data->save();

        return redirect('/category');

    }

}

