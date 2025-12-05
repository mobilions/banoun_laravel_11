<?php

namespace App\Http\Controllers;

use App\Models\Emailtemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Auth;

class EmailtemplateController extends Controller
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
        $title = "Email Template";
        $indexes = Emailtemplate::where('delete_status','0')->get();
        return view('emailtemplate.index',compact('title','indexes'));  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Email Template";
        return view('emailtemplate.create',compact('title')); 
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
            'message' => 'nullable|string',
            'message_ar' => 'nullable|string',
        ], [
            'name.required' => 'Subject is required.',
            'name.max' => 'Subject must not exceed 255 characters.',
        ]);

        try {
            $data = new Emailtemplate; 
            $data->name = $request->name;
            $data->message = $request->message;
            $data->message_ar = $request->message_ar;
            $data->delete_status = '0';
            $data->created_by = Auth::user()->id;
            $data->save();
            return redirect('/emailtemplate')->with('success', 'Email template created successfully.');
        } catch (\Exception $e) {
            \Log::error('Email template creation failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create email template. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Brand  $emailtemplate
     * @return \Illuminate\Http\Response
     */
    public function show(Emailtemplate $emailtemplate)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Brand  $emailtemplate
     * @return \Illuminate\Http\Response
     */
    public function edit(Emailtemplate $emailtemplate,$id)
    {
        $title = "Email Template";
        $log = Emailtemplate::where('id',$id)->first();
        return view('emailtemplate.edit',compact('title','log'));  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $emailtemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Emailtemplate $emailtemplate)
    {
        $this->validate($request, [
            'editid' => 'required|exists:emailtemplates,id',
            'name' => 'required|string|max:255',
            'message' => 'nullable|string',
            'message_ar' => 'nullable|string',
        ], [
            'editid.required' => 'Template ID is required.',
            'editid.exists' => 'Selected template does not exist.',
            'name.required' => 'Subject is required.',
            'name.max' => 'Subject must not exceed 255 characters.',
        ]);

        $data = Emailtemplate::find($request->editid);
        if (empty($data)) { 
            return redirect('/emailtemplate')->with('error', 'Email template not found.');
        }
        
        try {
            $data->name = $request->name;
            $data->message = $request->message;
            $data->message_ar = $request->message_ar;
            $data->updated_by = Auth::user()->id;
            $data->save();
            return redirect('/emailtemplate')->with('success', 'Email template updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Email template update failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update email template. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $emailtemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Emailtemplate $emailtemplate,$id)
    {
        $data = Emailtemplate::find($id);
        
        if (empty($data)) {
            return redirect('/emailtemplate')->with('error', 'Email template not found.');
        }
        
        try {
            $data->delete_status = 1;
            $data->save();
            return redirect('/emailtemplate')->with('success', 'Email template deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Email template deletion failed: ' . $e->getMessage());
            return redirect('/emailtemplate')->with('error', 'Failed to delete email template. Please try again.');
        }
    }
}
