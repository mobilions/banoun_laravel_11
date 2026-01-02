<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;



class HomeController extends Controller

{

    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function __construct()

    {

        $this->middleware('auth');

    }



    /**

     * Show the application dashboard.

     *

     * @return \Illuminate\Contracts\Support\Renderable

     */

    public function index()

    {
        return view('welcome');

    }

    public function mailcontent()
    {
        $msgcontent = 'Please confirm that this is your email address to keep your account secure.<br> This email will expire in 24 hours.';
        return view('layouts.mailcontent',compact('msgcontent'));
    }

}

