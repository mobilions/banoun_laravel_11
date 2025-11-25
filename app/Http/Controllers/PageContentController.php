<?php



namespace App\Http\Controllers;



use App\Models\PageContent;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use Auth;



class PageContentController extends Controller

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

        $title = "Page Contents";

        $indexes = PageContent::where('delete_status','0')->get();

        return view('pagecontent.index',compact('title','indexes'));  

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\PageContent  $pagecontent

     * @return \Illuminate\Http\Response

     */

    public function show(PageContent $pagecontent)

    {

        

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\PageContent  $pagecontent

     * @return \Illuminate\Http\Response

     */

    public function edit(PageContent $pagecontent,$id)

    {

        $title = "Page Contents";

        $log = PageContent::where('id',$id)->first();

        return view('pagecontent.edit',compact('title','log'));  

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\PageContent  $pagecontent

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, PageContent $pagecontent)

    {
        $this->validate($request, [
            'editid' => 'required|exists:page_contents,id',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
        ]);

        $data = PageContent::find($request->editid);

        if (empty($data)) { 
            return redirect('/pagecontent')->with('error', 'Page content not found.');
        }

        $data->description = $request->description;

        $data->description_ar = $request->description_ar;

        $data->updated_by=Auth::user()->id;

        $data->save();

        return redirect('/pagecontent');

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\PageContent  $pagecontent

     * @return \Illuminate\Http\Response

     */

    public function destroy(PageContent $pagecontent,$id)

    {

        $data = PageContent::find($id);

        $data->delete_status = 1;

        $data->save();

        return redirect('/pagecontent');

    }

}

