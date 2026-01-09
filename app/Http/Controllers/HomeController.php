<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\Models\CartMaster;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Productvariant;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Stock;
use App\Models\Orderstatus;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



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

        // Date ranges
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Sales Statistics
        $salesToday = CartMaster::whereDate('created_at', $today)->sum('grandtotal') ?? 0;
        $salesThisWeek = CartMaster::where('created_at', '>=', $thisWeek)->sum('grandtotal') ?? 0;
        $salesThisMonth = CartMaster::where('created_at', '>=', $thisMonth)->sum('grandtotal') ?? 0;
        $salesThisYear = CartMaster::where('created_at', '>=', $thisYear)->sum('grandtotal') ?? 0;
        $salesLastMonth = CartMaster::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->sum('grandtotal') ?? 0;
        
        // Calculate percentage change
        $monthlyChange = $salesLastMonth > 0 ? (($salesThisMonth - $salesLastMonth) / $salesLastMonth) * 100 : 0;

        // Order Counts
        $ordersToday = CartMaster::whereDate('created_at', $today)->count();
        $ordersThisWeek = CartMaster::where('created_at', '>=', $thisWeek)->count();
        $ordersThisMonth = CartMaster::where('created_at', '>=', $thisMonth)->count();
        $ordersThisYear = CartMaster::where('created_at', '>=', $thisYear)->count();

        // Order Status Statistics
        $orderStatuses = Orderstatus::all();
        $orderStatusData = [];
        foreach ($orderStatuses as $status) {
            $count = CartMaster::where('orderstatus', $status->id)->count();
            $orderStatusData[] = [
                'name' => $status->name,
                'count' => $count,
                'color' => $status->color ?? '#556ee6'
            ];
        }

        // Payment Type Statistics
        $paymentTypes = CartMaster::select('paymenttype', DB::raw('count(*) as count'))
            ->whereNotNull('paymenttype')
            ->groupBy('paymenttype')
            ->get();

        // Payment Status Statistics
        $paymentStatuses = CartMaster::select('paymentstatus', DB::raw('count(*) as count'))
            ->whereNotNull('paymentstatus')
            ->groupBy('paymentstatus')
            ->get();

        // Monthly Revenue Data (Last 12 months)
        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();
            $revenue = CartMaster::whereBetween('created_at', [$monthStart, $monthEnd])->sum('grandtotal') ?? 0;
            $monthlyRevenue[] = [
                'month' => $monthStart->format('M Y'),
                'revenue' => (float)$revenue
            ];
        }

        // Daily Revenue Data (Last 30 days)
        $dailyRevenue = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenue = CartMaster::whereDate('created_at', $date)->sum('grandtotal') ?? 0;
            $dailyRevenue[] = [
                'date' => $date->format('M d'),
                'revenue' => (float)$revenue
            ];
        }

        // Top Selling Products (by quantity sold)
        $topProducts = Cart::select('products.id', 'products.name', 'products.name_ar', DB::raw('COALESCE(SUM(carts.qty), 0) as total_qty'), DB::raw('COALESCE(SUM(carts.total_price), 0) as total_revenue'))
            ->join('products', 'carts.product_id', '=', 'products.id')
            ->where('carts.carted', 1)
            ->whereNotNull('carts.master_id')
            ->where('products.delete_status', 0)
            ->groupBy('products.id', 'products.name', 'products.name_ar')
            ->havingRaw('total_qty > 0')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Low Stock Products (available_quantity < 20)
        $lowStockProducts = Productvariant::with(['product', 'sizeVariant', 'colorVariant'])
            ->where('available_quantity', '<', 20)
            ->where('delete_status', 0)
            ->orderBy('available_quantity', 'asc')
            ->limit(10)
            ->get();

        // Recent Orders
        $recentOrders = CartMaster::with(['user', 'orderStatus'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Customer Statistics
        $totalCustomers = User::where('role', 'user')->where('is_verified', 1)->where('delete_status', 0)->count();
        $newCustomersThisMonth = User::where('role', 'user')
            ->where('is_verified', 1)
            ->where('delete_status', 0)
            ->where('created_at', '>=', $thisMonth)
            ->count();

        // Product Statistics
        $totalProducts = Product::where('delete_status', 0)->count();
        $totalBrands = Brand::where('delete_status', 0)->count();
        $totalCategories = Category::where('delete_status', 0)->count();
        $totalSubcategories = Subcategory::where('delete_status', 0)->count();

        // Order Status Counts for Pie Chart
        $orderStatusCounts = CartMaster::select('orderstatus', DB::raw('count(*) as count'))
            ->groupBy('orderstatus')
            ->get()
            ->map(function($item) {
                $status = Orderstatus::find($item->orderstatus);
                return [
                    'name' => $status ? $status->name : 'Unknown',
                    'count' => $item->count,
                    'color' => $status && $status->color ? $status->color : '#556ee6'
                ];
            });

        return view('welcome', compact(
            'salesToday', 'salesThisWeek', 'salesThisMonth', 'salesThisYear', 'monthlyChange',
            'ordersToday', 'ordersThisWeek', 'ordersThisMonth', 'ordersThisYear',
            'orderStatusData', 'paymentTypes', 'paymentStatuses',
            'monthlyRevenue', 'dailyRevenue',
            'topProducts', 'lowStockProducts', 'recentOrders',
            'totalCustomers', 'newCustomersThisMonth',
            'totalProducts', 'totalBrands', 'totalCategories', 'totalSubcategories',
            'orderStatusCounts'
        ));

    }

    public function mailcontent()

    {

        $msgcontent = 'Please confirm that this is your email address to keep your account secure.<br> This email will expire in 24 hours.';

        return view('layouts.mailcontent',compact('msgcontent'));

    }

}
