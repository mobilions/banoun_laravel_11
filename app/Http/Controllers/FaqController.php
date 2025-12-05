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



    public function index(Request $request)

    {
        // Get type from route name (terms, faq, about)
        $routeName = $request->route()->getName();
        $type = 'faq'; // default
        
        if($routeName == 'terms'){
            $type = 'terms';
            $title = "Terms & Conditions";
        } elseif($routeName == 'faq'){
            $type = 'faq';
            $title = "FAQ";
        } elseif($routeName == 'about'){
            $type = 'about';
            $title = "About Us";
        } else {
            $title = "FAQ";
        }

        $indexes = Faq::where('delete_status','0')
            ->where('type',$type)
            ->orderByDesc('created_at')
            ->get();
        $val = $type; // Pass type as val to view

        return view('faq.index',compact('title','indexes','val'));  

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create(Request $request)

    {
        // Get type from route name (terms, faq, about)
        $routeName = $request->route()->getName();
        $type = 'faq'; // default
        
        if($routeName == 'termscreate'){
            $type = 'terms';
            $title = "Terms & Conditions";
        } elseif($routeName == 'faqcreate'){
            $type = 'faq';
            $title = "FAQ";
        } elseif($routeName == 'aboutcreate'){
            $type = 'about';
            $title = "About Us";
        } else {
            $title = "FAQ";
        }
        $val = $type; // Pass type as val to view

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
        // Get type from route name
        $routeName = $request->route()->getName();
        $type = 'faq'; // default
        
        if($routeName == 'termsstore'){
            $type = 'terms';
        } elseif($routeName == 'faqstore'){
            $type = 'faq';
        } elseif($routeName == 'aboutstore'){
            $type = 'about';
        }

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
        ], [
            'name.required' => 'Title is required.',
            'name.max' => 'Title must not exceed 255 characters.',
            'name_ar.max' => 'Arabic title must not exceed 255 characters.',
            'description.string' => 'Description must be a valid text.',
            'description_ar.string' => 'Arabic description must be a valid text.',
        ]);

        try {
            $data = new Faq; 

            $data->title = $request->name;
            $data->content = $request->description;
            $data->title_ar = $request->name_ar;
            $data->content_ar = $request->description_ar;
            $data->type = $type;
            $data->delete_status = '0';
            $data->created_by = Auth::user()->id;

            $data->save();

            // Redirect based on type
            $redirectUrl = '/' . $type;
            return redirect($redirectUrl)->with('success', 'Record created successfully.');
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
        $log = Faq::where('id',$id)->where('delete_status','0')->first();

        if (empty($log)) {
            return redirect()->back()->with('error', 'Record not found.');
        }

        $val = $log->type;

        if($val=='about'){
            $title = "About Us";
        } elseif($val=='faq'){
            $title = "FAQ";
        } elseif($val=='terms'){
            $title = "Terms & Conditions";
        } else {
            $title = "FAQ";
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
            'editid' => 'required|exists:qa_details,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
        ], [
            'editid.required' => 'Record ID is required.',
            'editid.exists' => 'Selected record does not exist.',
            'name.required' => 'Title is required.',
            'name.max' => 'Title must not exceed 255 characters.',
            'name_ar.max' => 'Arabic title must not exceed 255 characters.',
            'description.string' => 'Description must be a valid text.',
            'description_ar.string' => 'Arabic description must be a valid text.',
        ]);

        $data = Faq::where('id', $request->editid)
            ->where('delete_status', '0')
            ->first();

        if (empty($data)) { 
            $type = 'faq';
            return redirect('/' . $type)->with('error', 'Record not found or has been deleted.');
        }

        $type = $data->type;

        try {
            $data->title = $request->name;
            $data->content = $request->description;
            $data->title_ar = $request->name_ar;
            $data->content_ar = $request->description_ar;
            $data->updated_by = Auth::user()->id;

            $data->save();

            // Redirect based on type from the record
            $redirectUrl = '/' . $data->type;
            return redirect($redirectUrl)->with('success', 'Record updated successfully.');
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

    public function destroy(Faq $faq, $id)

    {
        $data = Faq::where('id', $id)
            ->where('delete_status', '0')
            ->first();

        if (empty($data)) {
            $type = 'faq';
            return redirect('/' . $type)->with('error', 'Record not found or already deleted.');
        }

        $type = $data->type;

        try {
            $data->delete_status = 1;
            $data->updated_by = Auth::user()->id;
            $data->save();

            return redirect('/' . $type)->with('success', 'Record deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('FAQ deletion failed: ' . $e->getMessage());
            return redirect('/' . $type)->with('error', 'Failed to delete record. Please try again.');
        }

    }

}

