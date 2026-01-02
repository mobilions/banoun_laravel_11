<?php



namespace App\Http\Controllers;



use App\Models\User;

use App\Models\Wishlist;

use App\Models\Userkids;

use App\Models\UserAddress;

use App\Models\CartMaster;

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

        $userAddress=UserAddress::with('userarea')->where('user_id',$id)->get();
        $cartMasters=CartMaster::where('user_id',$id)->get();

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
        ], [
            'editid.required' => 'Customer ID is required.',
            'editid.exists' => 'Selected customer does not exist.',
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'phone.max' => 'Phone number must not exceed 20 characters.',
        ]);

        $data = User::find($request->editid);

        if (empty($data)) { 
            return redirect('/customer')->with('error', 'Customer not found.');
        }

        try {
            $data->name = $request->name;
            $data->email = $request->email;
            $data->phone = $request->phone;
            $data->save();

            return redirect('/customer')->with('success', 'Customer updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Customer update failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update customer. Please try again.');
        }

    }

    public function destroy(User $user,$id)

    {
        $data = User::find($id);

        if (empty($data)) { 
            return redirect('/customer')->with('error', 'Customer not found.');
        }

        try {
            $data->delete_status = '1';
            $data->save();

            return redirect('/customer')->with('success', 'Customer deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Customer deletion failed: ' . $e->getMessage());
            return redirect('/customer')->with('error', 'Failed to delete customer. Please try again.');
        }

    }



   

}

