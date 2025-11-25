<?php



namespace App\Http\Controllers;



use App\Models\User;

use App\Models\Wishlist;

use App\Models\Userkids;

use App\Models\UserAddress;

use App\Models\Cartmaster;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use Auth;



class CustomerController extends Controller

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

        $title = "Customer";

        $indexes = User::active()->where('role','user')->where('is_verified',1)->get();

        return view('customer.index',compact('title','indexes'));  

    }



    public function view(User $user,$id)

    {

        $title = "Customer Info";

        $log = User::where('id',$id)->first();

        $kids = Userkids::where('user_id',$id)->get();

        $userAddress=UserAddress::where('user_id',$id)->get();

        $cartMasters=Cartmaster::where('user_id',$id)->get();

        $wishlists=Wishlist::where('created_by',$id)->where('delete_status',0)->get();

        return view('customer.view',compact('title','log','kids','userAddress','cartMasters','wishlists'));  

    }

    public function edit(User $user,$id)
    {

        $title = "Customer";

        $log = user::where('id',$id)->first();

        return view('customer.edit',compact('title','log'));  

    }


    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'editid' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$request->editid,
            'phone' => 'nullable|string|max:20',
        ]);

        $data = User::find($request->editid);

        if (empty($data)) { 
            return redirect('/customer')->with('error', 'Customer not found.');
        }

        $data->name = $request->name;

        $data->email = $request->email;

        $data->phone = $request->phone;

        $data->save();

        return redirect('/customer');

    }

    public function destroy(User $user,$id)

    {
        $data = User::find($id);

        if (empty($data)) { 
            return redirect('/customer')->with('error', 'Customer not found.');
        }

        $data->delete_status = '1';

        $data->save();

        return redirect('/customer');

    }



   

}

