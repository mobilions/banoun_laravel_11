<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Auth;

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
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);
        
        $password=Hash::make($request->password);
        $data = new User; 
        $data->name = $request->name;
        $data->email = $request->email;
        $data->password = $password;
        $data->role = 'admin';
        $data->save();
        return redirect('/users');
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
            'email' => 'required|email|max:255|unique:users,email,'.$request->editid,
        ]);

        $data = User::find($request->editid);

        if (empty($data)) { 
            return redirect('/users')->with('error', 'User not found.');
        }

        $data->name = $request->name;

        $data->email = $request->email;

        $data->save();

        return redirect('/users');

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
