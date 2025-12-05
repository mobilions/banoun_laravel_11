<?php



namespace App\Http\Controllers;



use App\Models\Faq;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use Auth;



class FaqController extends Controller

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



    public function index($val)

    {

        if($val=='about'){

            $title = "About Us";

        }

        if($val=='faq'){

            $title = "FAQ";

        }

        if($val=='terms'){

            $title = "Terms & Condtions";

        }

        

        $indexes = Faq::where('delete_status','0')->where('type',$val)->get();

        return view('faq.index',compact('title','indexes','val'));  

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create($val)

    {

        if($val=='about'){

            $title = "About Us";

        }

        if($val=='faq'){

            $title = "FAQ";

        }

        if($val=='terms'){

            $title = "Terms & Condtions";

        }

        return view('faq.create',compact('title','val')); 

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
            'type' => 'required|string|in:about,faq,terms',
        ], [
            'name.required' => 'Name is required.',
            'name.max' => 'Name must not exceed 255 characters.',
            'name_ar.max' => 'Arabic name must not exceed 255 characters.',
            'type.required' => 'Type is required.',
            'type.in' => 'Invalid type selected.',
        ]);

        try {
            $data = new Faq; 

            $data->title = $request->name;
            $data->content = $request->description;
            $data->title_ar = $request->name_ar;
            $data->content_ar = $request->description_ar;
            $data->type = $request->type;
            $data->delete_status = '0';
            $data->created_by = Auth::user()->id;

            $data->save();

            return redirect('/faq/'.$request->type)->with('success', 'Record created successfully.');
        } catch (\Exception $e) {
            \Log::error('FAQ creation failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create record. Please try again.');
        }

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Faq  $brand

     * @return \Illuminate\Http\Response

     */

    public function show(Faq $brand)

    {

        

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Brand  $brand

     * @return \Illuminate\Http\Response

     */

    public function edit(Faq $faq,$id)

    {

        $log = Faq::where('id',$id)->first();

        $val = $log->type;

        if($val=='about'){

            $title = "About Us";

        }

        if($val=='faq'){

            $title = "FAQ";

        }

        if($val=='terms'){

            $title = "Terms & Condtions";

        }

        return view('faq.edit',compact('title','log'));  

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Brand  $brand

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, Faq $faq)

    {
        $this->validate($request, [
            'editid' => 'required|exists:faqs,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'type' => 'required|string|in:about,faq,terms',
        ], [
            'editid.required' => 'Record ID is required.',
            'editid.exists' => 'Selected record does not exist.',
            'name.required' => 'Name is required.',
            'name.max' => 'Name must not exceed 255 characters.',
            'name_ar.max' => 'Arabic name must not exceed 255 characters.',
            'type.required' => 'Type is required.',
            'type.in' => 'Invalid type selected.',
        ]);

        $data = Faq::find($request->editid);

        if (empty($data)) { 
            return redirect('/faq/'.$request->type)->with('error', 'Record not found.');
        }

        try {
            $data->title = $request->name;
            $data->content = $request->description;
            $data->title_ar = $request->name_ar;
            $data->content_ar = $request->description_ar;
            $data->updated_by = Auth::user()->id;

            $data->save();

            return redirect('/faq/'.$request->type)->with('success', 'Record updated successfully.');
        } catch (\Exception $e) {
            \Log::error('FAQ update failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update record. Please try again.');
        }

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Brand  $brand

     * @return \Illuminate\Http\Response

     */

    public function destroy(Faq $faq,$id,$val)

    {
        $data = Faq::find($id);

        if (empty($data)) {
            return redirect('/faq/'.$val)->with('error', 'Record not found.');
        }

        try {
            $data->delete_status = 1;
            $data->save();

            return redirect('/faq/'.$val)->with('success', 'Record deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('FAQ deletion failed: ' . $e->getMessage());
            return redirect('/faq/'.$val)->with('error', 'Failed to delete record. Please try again.');
        }

    }

}

