<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Topcollection;
use App\Models\Carousal;
use App\Models\Usbanner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $topcollections = Topcollection::where('delete_status','0')->get();
        $carousals = Carousal::where('delete_status','0')->get();
        $usbanners = Usbanner::where('delete_status','0')->get();
        return view('Front.home',compact('carousals','topcollections','usbanners'));
    }
}
