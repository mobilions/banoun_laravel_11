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



    public function index(Request $request)

    {

        $title = "Customer";

        $this->validate($request, [
            'search' => 'nullable|string|max:255',
            'is_verified' => 'nullable',
            'min_credit' => 'nullable|numeric|min:0',
            'max_credit' => 'nullable|numeric|min:0|gte:min_credit',
        ]);

        $query = User::active()->where('role','user');

        if ($request->filled('is_verified')) {
            if ($request->is_verified !== '') {
                $query->where('is_verified', (int)$request->is_verified);
            }
        } else {
            $query->where('is_verified', 1);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('min_credit') && $request->min_credit) {
            $query->where('credit_balance', '>=', $request->min_credit);
        }
        if ($request->has('max_credit') && $request->max_credit) {
            $query->where('credit_balance', '<=', $request->max_credit);
        }

        $indexes = $query->orderByDesc('created_at')->get();

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
            'phone' => 'nullable|string|max:20',
        ], [
            'editid.required' => 'Customer ID is required.',
            'editid.exists' => 'Selected customer does not exist.',
            'name.required' => 'Name is required.',
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

