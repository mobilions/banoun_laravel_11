<?php



namespace App\Http\Controllers;



use App\Models\Setting;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use Auth;



class SettingController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()

    {

        $title = 'Settings';

        $setting = Setting::where('delete_status','0')->first();

        return view('auth.setting',compact('setting','title'));

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        //

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
            'company' => 'required|string|max:255',
            'company_ar' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'support_phone' => 'nullable|string|max:20',
            'support_email' => 'nullable|email|max:255',
            'location' => 'nullable|string|max:500',
            'header' => 'nullable|string|max:500',
            'header_ar' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'facebook' => 'nullable|url|max:255',
            'whatsapp' => 'nullable|string|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'google' => 'nullable|url|max:255',
            'giftwrap_price' => 'nullable|numeric|min:0',
            'imgfile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'imgfile_val' => 'nullable|string',
        ]);

        Setting::where('delete_status','0')->update(['delete_status' => '1']);



        $imgurl    = '';

        $path   = $request->file('imgfile');

        if (!empty($path)) {

            $store  = Storage::putFile('public/image', $path);

            //$imgurl    = Storage::url($store);

            //$imgurl = url('/').'/storage/app/'.$store;

            $imgurl = config('app.imgurl').basename($store);

        }

        else{ $imgurl=$request->imgfile_val; }



        $data = new Setting; 

        $data->company = $request->company;

        $data->company_ar = $request->company_ar;

        $data->contact_person = $request->contact_person;

        $data->phone = $request->phone;

        $data->email = $request->email;

        $data->support_phone = $request->support_phone;

        $data->support_email = $request->support_email;

        $data->location = $request->location;

        $data->header = $request->header;

        $data->header_ar = $request->header_ar;

        $data->support_email = $request->support_email;

        $data->description = $request->description;

        $data->description_ar = $request->description_ar;

        $data->facebook = $request->facebook;

        $data->whatsapp = $request->whatsapp;

        $data->twitter = $request->twitter;

        $data->instagram = $request->instagram;

        $data->google = $request->google;

        $data->giftwrap_price = $request->giftwrap_price;

        $data->imageurl    = $imgurl;

        $data->created_by=Auth::user()->id;

        $data->save();

        return redirect()->back();

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Models\Setting  $setting

     * @return \Illuminate\Http\Response

     */

    public function show(Setting $setting)

    {

        //

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Models\Setting  $setting

     * @return \Illuminate\Http\Response

     */

    public function edit(Setting $setting)

    {

        //

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Models\Setting  $setting

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, Setting $setting)

    {

        //

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Models\Setting  $setting

     * @return \Illuminate\Http\Response

     */

    public function destroy(Setting $setting)

    {

        //

    }

}

