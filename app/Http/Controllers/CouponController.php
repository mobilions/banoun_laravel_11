<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class CouponController extends Controller
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
        $title = "Coupons";
        $indexes = Coupon::where('delete_status','0')->get();
        return view('coupon.index',compact('title','indexes'));  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Coupons";
        return view('coupon.create',compact('title')); 
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
            'price_type' => 'required|string|in:Percentage,Price,FreeDelivery',
            'coupon_val' => 'required|numeric|min:0',
            'coupon_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('coupons', 'coupon_code')
                    ->where('delete_status', '0')
            ],
            'coupon_code_ar' => 'nullable|string|max:255',
        ], [
            'price_type.required' => 'Coupon type is required.',
            'price_type.in' => 'Invalid coupon type selected.',
            'coupon_val.required' => 'Coupon value is required.',
            'coupon_val.numeric' => 'Coupon value must be a number.',
            'coupon_val.min' => 'Coupon value must be at least 0.',
            'coupon_code.required' => 'Coupon code is required.',
            'coupon_code.max' => 'Coupon code must not exceed 255 characters.',
            'coupon_code.unique' => 'This coupon code already exists.',
            'coupon_code_ar.max' => 'Arabic coupon code must not exceed 255 characters.',
        ]);
        
        try {
            $data = new Coupon; 
            $data->coupon_type = 'All';
            $data->coupon_type_id = 0;
            $data->price_type = $request->price_type;
            $data->coupon_val = $request->coupon_val;
            $data->coupon_code = $request->coupon_code;
            $data->coupon_code_ar = $request->coupon_code_ar;
            $data->delete_status = '0';
            $data->created_by = Auth::user()->id;
            $data->save();
            return redirect('/coupon')->with('success', 'Coupon created successfully.');
        } catch (\Exception $e) {
            \Log::error('Coupon creation failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create coupon. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon,$id)
    {
        $title = "Coupon";
        $log = Coupon::where('id',$id)->first();
        return view('coupon.edit',compact('title','log'));  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {
        $this->validate($request, [
            'editid' => 'required|exists:coupons,id',
            'price_type' => 'required|string|in:Percentage,Price,FreeDelivery',
            'coupon_val' => 'required|numeric|min:0',
            'coupon_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('coupons', 'coupon_code')
                    ->ignore($request->editid)
                    ->where('delete_status', '0')
            ],
            'coupon_code_ar' => 'nullable|string|max:255',
        ], [
            'editid.required' => 'Coupon ID is required.',
            'editid.exists' => 'Selected coupon does not exist.',
            'price_type.required' => 'Coupon type is required.',
            'price_type.in' => 'Invalid coupon type selected.',
            'coupon_val.required' => 'Coupon value is required.',
            'coupon_val.numeric' => 'Coupon value must be a number.',
            'coupon_val.min' => 'Coupon value must be at least 0.',
            'coupon_code.required' => 'Coupon code is required.',
            'coupon_code.max' => 'Coupon code must not exceed 255 characters.',
            'coupon_code.unique' => 'This coupon code already exists.',
            'coupon_code_ar.max' => 'Arabic coupon code must not exceed 255 characters.',
        ]);
    
        $data = Coupon::find($request->editid);
        if (empty($data)) { 
            return redirect('/coupon')->with('error', 'Coupon not found.');
        }
        
        try {
            $data->coupon_type = 'All';
            $data->coupon_type_id = 0;
            $data->price_type = $request->price_type;
            $data->coupon_val = $request->coupon_val;
            $data->coupon_code = $request->coupon_code;
            $data->coupon_code_ar = $request->coupon_code_ar;
            $data->updated_by = Auth::user()->id;
            $data->save();
            return redirect('/coupon')->with('success', 'Coupon updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Coupon update failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update coupon. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon,$id)
    {
        $data = Coupon::find($id);
        
        if (empty($data)) {
            return redirect('/coupon')->with('error', 'Coupon not found.');
        }
        
        try {
            $data->delete_status = 1;
            $data->save();
            return redirect('/coupon')->with('success', 'Coupon deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Coupon deletion failed: ' . $e->getMessage());
            return redirect('/coupon')->with('error', 'Failed to delete coupon. Please try again.');
        }
    }
}
