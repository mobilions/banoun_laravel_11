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
            'imgfile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Info is required.',
            'name.max' => 'Info must not exceed 255 characters.',
            'name_ar.max' => 'Arabic info must not exceed 255 characters.',
            'imgfile.image' => 'The file must be an image.',
            'imgfile.mimes' => 'The image must be jpeg, png, jpg, gif, or svg.',
            'imgfile.max' => 'The image size must not exceed 2MB.',
        ]);

        try {
            $imgurl = '';

            if ($request->hasFile('imgfile') && $request->file('imgfile')->isValid()) {
                $file = $request->file('imgfile');
                $path = $file->store('public/image');
                $filename = basename($path);
                $imgurl = config('app.imgurl') . $filename;
            }

            $data = new Delivery; 
            $data->name = $request->name;
            $data->name_ar = $request->name_ar;
            $data->imageurl = $imgurl;
            $data->delete_status = '0';
            $data->created_by = Auth::user()->id;
            $data->save();

            return redirect('/delivery')->with('success', 'Delivery info created successfully.');
        } catch (\Exception $e) {
            \Log::error('Delivery creation failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create delivery info. Please try again.');
        }
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
            'editid' => 'required|exists:delivery_options,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'imgfile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'imgfile_val' => 'nullable|string',
        ], [
            'editid.required' => 'Record ID is required.',
            'editid.exists' => 'Selected record does not exist.',
            'name.required' => 'Info is required.',
            'name.max' => 'Info must not exceed 255 characters.',
            'name_ar.max' => 'Arabic info must not exceed 255 characters.',
            'imgfile.image' => 'The file must be an image.',
            'imgfile.mimes' => 'The image must be jpeg, png, jpg, gif, or svg.',
            'imgfile.max' => 'The image size must not exceed 2MB.',
        ]);

        $data = Delivery::find($request->editid);

        if (empty($data)) { 
            return redirect('/delivery')->with('error', 'Delivery record not found.');
        }

        try {
            $imgurl = $data->imageurl;

            if ($request->hasFile('imgfile') && $request->file('imgfile')->isValid()) {
                $file = $request->file('imgfile');
                if (!empty($data->imageurl)) {
                    $oldFilename = basename($data->imageurl);
                    $oldPath = 'public/image/' . $oldFilename;
                    if (Storage::exists($oldPath)) {
                        Storage::delete($oldPath);
                    }
                }
                $path = $file->store('public/image');
                $filename = basename($path);
                $imgurl = config('app.imgurl') . $filename;
                
            } elseif ($request->filled('imgfile_val')) {
                $imgurl = $request->imgfile_val;
            }

            $data->name = $request->name;
            $data->name_ar = $request->name_ar;
            $data->imageurl = $imgurl;
            $data->updated_by = Auth::user()->id;
            $data->save();

            return redirect('/delivery')->with('success', 'Delivery info updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Delivery update failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update delivery info. Please try again.');
        }
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

        if (empty($data)) {
            return redirect('/delivery')->with('error', 'Delivery record not found.');
        }

        try {
            $data->delete_status = 1;
            $data->save();

            return redirect('/delivery')->with('success', 'Delivery info deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Delivery deletion failed: ' . $e->getMessage());
            return redirect('/delivery')->with('error', 'Failed to delete delivery info. Please try again.');
        }

    }

}

