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
            'search' => 'nullable|string|max:255',
            'order_status' => 'nullable|integer',
            'payment_type' => 'nullable|string',
            'min_amount' => 'nullable|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
        ]);

        $fromdate = $request->input('fromdate', date('Y-m-01'));
        $todate = $request->input('todate', date('Y-m-d'));
        
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));

        $query = CartMaster::with(['user', 'orderStatus'])
            ->whereBetween('created_at',[$fromdate, $todate1]);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by order status
        if ($request->has('order_status') && $request->order_status) {
            $query->where('orderstatus', $request->order_status);
        }

        // Filter by payment type
        if ($request->has('payment_type') && $request->payment_type) {
            $query->where('paymenttype', $request->payment_type);
        }

        // Filter by amount range
        if ($request->has('min_amount') && $request->min_amount) {
            $query->where('grandtotal', '>=', $request->min_amount);
        }
        if ($request->has('max_amount') && $request->max_amount) {
            $query->where('grandtotal', '<=', $request->max_amount);
        }

        $indexes = $query->orderByDesc('created_at')->get();

        // For filters dropdown
        $orderStatuses = \App\Models\Orderstatus::all();
        $paymentTypes = CartMaster::distinct()->whereNotNull('paymenttype')->pluck('paymenttype')->filter();

        return view('order.index',compact('title','indexes','fromdate','todate','orderStatuses','paymentTypes'));  
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

        $statusMailMap = [
            1 => 'payment_paid',
            2 => 'order_processing',
            3 => 'order_shipped',
            4 => 'order_out_for_delivery',
            5 => 'order_delivered',
        ];

        if (isset($statusMailMap[$val])) {
           $template = EmailTemplate::byType($statusMailMap[$val]);
           if ($template) {
                $subject = $template->name;
                $msgcontent = $template->message;
                $content = view('layouts.mailcontent', compact('msgcontent'))->render();
                $this->sendMail($user->email, $content, $subject);
            }
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
