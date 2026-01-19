<?php
namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\CartMaster;
use App\Models\OrderLog;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Productvariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
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
    public function order(Request $request)
    {
        $title = "Orders Report";

        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));

        $indexes = CartMaster::whereBetween('created_at',[$fromdate, $todate1])->get();

        return view('report.order',compact('title','indexes','fromdate','todate'));  
    }

    public function stock()
    {
        $title = "Stocks Report";
        $indexes = Productvariant::join('products', 'productvariants.product_id', '=', 'products.id')->addSelect('products.name as product','products.name_ar as products_ar','products.category_id','products.subcategory_id','products.brand_id','productvariants.*')->get();
        return view('report.stock',compact('title','indexes'));
    }

    public function getNotifications(Request $request)
    {
        try {
            $user = Auth::user();
            
            $notifications = DB::table('notifications')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($notification) {
                    $notification->time_ago = $this->timeAgo($notification->created_at);
                    return $notification;
                });
            
            $unread_count = DB::table('notifications')
               ->where('is_read', false)
                ->count();
            
            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => $unread_count
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch notifications'
            ], 500);
        }
    }

    public function markNotificationRead(Request $request)
    {
        try {
            $user = Auth::user();
            
            if ($request->notification_id) {
                DB::table('notifications')
                    ->where('id', $request->notification_id)
                    ->update(['is_read' => true]);
            } else {
                DB::table('notifications')
                    ->update(['is_read' => true]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update notification'
            ], 500);
        }
    }

    private function timeAgo($datetime)
    {
        $time = strtotime($datetime);
        $diff = time() - $time;
        
        if ($diff < 60) {
            return $diff . ' sec ago';
        } elseif ($diff < 3600) {
            return floor($diff / 60) . ' min ago';
        } elseif ($diff < 86400) {
            return floor($diff / 3600) . ' hour' . (floor($diff / 3600) > 1 ? 's' : '') . ' ago';
        } else {
            return floor($diff / 86400) . ' day' . (floor($diff / 86400) > 1 ? 's' : '') . ' ago';
        }
    }

}
