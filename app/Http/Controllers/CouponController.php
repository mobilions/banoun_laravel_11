<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


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
            'price_type' => 'required|string',
            'coupon_val' => 'required|numeric|min:0',
            'coupon_code' => 'required|string|max:255|unique:coupons,coupon_code',
            'coupon_code_ar' => 'nullable|string|max:255',
        ]);
        
        $data = new Coupon; 
        $data->coupon_type = 'All';
        $data->coupon_type_id = 0;
        $data->price_type = $request->price_type;
        $data->coupon_val = $request->coupon_val;
        $data->coupon_code = $request->coupon_code;
        $data->coupon_code_ar = $request->coupon_code_ar;
        $data->created_by=Auth::user()->id;
        $data->save();
        return redirect('/coupon');
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
            'price_type' => 'required|string',
            'coupon_val' => 'required|numeric|min:0',
            'coupon_code' => 'required|string|max:255|unique:coupons,coupon_code,'.$request->editid,
            'coupon_code_ar' => 'nullable|string|max:255',
        ]);
    
        $data = Coupon::find($request->editid);
        if (empty($data)) { 
            return redirect('/coupon')->with('error', 'Coupon not found.');
        }
        $data->coupon_type = 'All';
        $data->coupon_type_id = 0;
        $data->price_type = $request->price_type;
        $data->coupon_val = $request->coupon_val;
        $data->coupon_code = $request->coupon_code;
        $data->coupon_code_ar = $request->coupon_code_ar;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect('/coupon');
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
        $data->delete_status = 1;
        $data->save();
        return redirect('/coupon');
    }
}
