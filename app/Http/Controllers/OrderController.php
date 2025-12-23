<?php
namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\CartMaster;
use App\Models\OrderLog;
use App\Models\Emailtemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Auth;
use Mail;
class OrderController extends Controller
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
        $title = "Orders";

        $this->validate($request, [
            'fromdate' => 'nullable|date',
            'todate' => 'nullable|date|after_or_equal:fromdate',
        ]);

        $fromdate = $request->input('fromdate', date('Y-m-01'));
        $todate = $request->input('todate', date('Y-m-d'));
        
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));

        $indexes = CartMaster::with(['user'])
            ->whereBetween('created_at',[$fromdate, $todate1])
            ->orderByDesc('created_at')
            ->get();

        return view('order.index',compact('title','indexes','fromdate','todate'));  
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
    public function view($id)
    {
        $title = "View Order";
        $order = CartMaster::with(['user'])->where('id',$id)->firstOrFail();
        $this->authorizeOrderAccess($order);
        $orderlist = Cart::with(['product','variant'])->where('master_id',$id)->get();
        $order_tracks = OrderLog::with(['status'])->where('cartmaster_id',$id)->orderByDesc('created_at')->get();
        return view('order.view',compact('title','order','orderlist','order_tracks'));  
    }

    public function updateorderstatus($id='',$val)
    {
        $request = request();
        $request->merge(['id' => $id, 'val' => $val]);
        
        $this->validate($request, [
            'id' => 'required|exists:cartmasters,id',
            'val' => 'required|integer|in:1,2,3,4,5',
        ]);
        
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $order = CartMaster::with('user')->findOrFail($id);
        $user = $order->user;

        if (!$user || !$user->email) {
            return redirect()->back()->with('error', 'User email not found for this order.');
        }

        $order->orderstatus = $val;
        $order->updated_by = Auth::user()->id;
        $order->save();

        $orderLog = new OrderLog; 
        $orderLog->cartmaster_id = $id;
        $orderLog->status_id = $val;
        $orderLog->created_by = Auth::user()->id;
        $orderLog->save();

        if($val==2){
            $subject = Emailtemplate::FindSubject('Out for Delivery');
            $msgcontent = '';
            $msgcontent .= Emailtemplate::FindContent('Out for Delivery');
            $content = view('layouts.mailcontent',compact('msgcontent'))->render();
            $this->sendMail($user->email,$content,$subject);
        }

        if($val==3){
            $subject = Emailtemplate::FindSubject('Order Delivered');
            $msgcontent = '';
            $msgcontent .= Emailtemplate::FindContent('Order Delivered');
            $content = view('layouts.mailcontent',compact('msgcontent'))->render();
            $this->sendMail($user->email,$content,$subject);
        }

        if($val==4){
            $subject = Emailtemplate::FindSubject('Request for Return & Refund');
            $msgcontent = '';
            $msgcontent .= Emailtemplate::FindContent('Request for Return & Refund');
            $content = view('layouts.mailcontent',compact('msgcontent'))->render();
            $this->sendMail($user->email,$content,$subject);
        }

        if($val==5){
            $subject = Emailtemplate::FindSubject('Order Cancelled');
            $msgcontent = '';
            $msgcontent .= Emailtemplate::FindContent('Order Cancelled');
            $content = view('layouts.mailcontent',compact('msgcontent'))->render();
            $this->sendMail($user->email,$content,$subject);
        }

        return redirect('order/'.$id.'/view');


    }

    protected function authorizeOrderAccess(CartMaster $order)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized.');
        }
        if ($user->role !== 'admin' && $order->user_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }
    }

    public static function sendMail($emailtoids,$content,$subject)
    {
        $data = array( 'replytoemail' =>$emailtoids ,'subject' => $subject, 'content' => $content);
        $certificate = '';
        Mail::send('layouts.email', $data, function ($m) use ($data, $emailtoids, $certificate)  {
        $m->to($emailtoids, '')->subject($data['subject']);
        if($certificate != ''){
        $m->attach($certificate);
        }
        });
    }


    
}
