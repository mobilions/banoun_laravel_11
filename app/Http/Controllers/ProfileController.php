<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
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
        $title = "Users";
        $indexes = User::where('role','admin')->where('delete_status','0')->get();
        return view('user.index',compact('title','indexes'));  
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Users";
        return view('user.create',compact('title')); 
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
            'email' => [
            'required',
            'email',
            'max:255',
                Rule::unique('users', 'email')
                    ->where('role', 'admin') 
            ],
            'password' => 'required|string|min:6',
        ], [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
        ]);
        
        try {
            $password = Hash::make($request->password);
            $data = new User; 
            $data->name = $request->name;
            $data->email = $request->email;
            $data->password = $password;
            $data->role = 'admin';
            $data->delete_status = '0';
            $data->save();
            return redirect('/users')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            \Log::error('User creation failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create user. Please try again.');
        }
    }

    public function edit(User $user,$id)
    {

        $title = "user";

        $log = user::where('id',$id)->first();

        return view('user.edit',compact('title','log'));  

    }


    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'editid' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => [
            'required',
            'email',
            'max:255',
            Rule::unique('users', 'email')
                    ->where('role', 'admin')  
                    ->ignore($request->editid) 
            ],
            'password' => 'nullable|string|min:6',
        ], [
            'editid.required' => 'User ID is required.',
            'editid.exists' => 'Selected user does not exist.',
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.min' => 'Password must be at least 6 characters.',
        ]);

        $data = User::find($request->editid);

        if (empty($data)) { 
            return redirect('/users')->with('error', 'User not found.');
        }

        try {
            $data->name = $request->name;
            $data->email = $request->email;
            
            // Update password only if provided
            if (!empty($request->password)) {
                $data->password = Hash::make($request->password);
            }
            
            $data->save();

            return redirect('/users')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            \Log::error('User update failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update user. Please try again.');
        }

    }

    public function destroy(User $user,$id)

    {
        $data = User::find($id);

        if (empty($data)) { 
            return redirect('/users')->with('error', 'User not found.');
        }

        $data->delete_status = '1';

        $data->save();

        return redirect('/users');

    }
    
}
