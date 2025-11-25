<?php
require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Bootstrap console kernel so facades like Auth work fully
$console = $app->make(Illuminate\Contracts\Console\Kernel::class);
$console->bootstrap();

echo "Starting automated tests...\n";

// Ensure a test user exists
use App\Models\User;
use Illuminate\Support\Facades\Hash as FH;

$email = 'test@example.com';
$user = User::where('email', $email)->first();
if (!$user) {
    $user = User::create([
        'name' => 'Test User',
        'email' => $email,
        'password' => FH::make('secret'),
        'credit_balance' => 0.00,
        'is_verified' => 1,
        'delete_status' => 0,
    ]);
    echo "Created test user {$email}\n";
} else {
    echo "Using existing user {$email}\n";
}

// Helper to dispatch a kernel request and return decoded json or raw
function dispatch_request($kernel, $method, $uri, $data = [], $headers = []) {
    $request = Request::create($uri, $method, $data);
    foreach ($headers as $k => $v) {
        $request->headers->set($k, $v);
    }
    $response = $kernel->handle($request);
    $status = $response->getStatusCode();
    $content = $response->getContent();
    $kernel->terminate($request, $response);
    $decoded = null;
    $ct = $response->headers->get('Content-Type');
    if (str_contains($ct ?? '', 'application/json')) {
        $decoded = json_decode($content, true);
    }
    return ['status' => $status, 'content' => $content, 'json' => $decoded];
}

// 1) API: Register -> verifyotp -> login -> logout
echo "\nAPI Auth tests:\n";

// Register a new phone
$phone = '900' . rand(10000, 99999);
$registerData = ['name' => 'ApiTest', 'phone' => $phone];
$res = dispatch_request($kernel, 'POST', '/api/register', $registerData);
echo "POST /api/register -> status {$res['status']}\n";
if ($res['status'] !== 200 && $res['status'] !== 302) {
    echo "Register failed: {$res['content']}\n";
} else {
    // Try to parse returned JSON for userId
    $json = $res['json'] ?? null;
    $userId = $json['data']['userId'] ?? null;
    if (!$userId) {
        echo "Could not determine userId from register response.\n";
    } else {
        echo "Registered userId: {$userId}\n";
        // Set known OTP on that user so we can verify
        $u = User::find($userId);
        if ($u) {
            $otpPlain = '123456';
            $u->otp = FH::make($otpPlain);
            $u->is_verified = 0;
            $u->save();
            echo "Patched user OTP to known value.\n";

            // Verify OTP
            $verifyRes = dispatch_request($kernel, 'POST', '/api/verifyotp', ['userId' => $userId, 'otp' => $otpPlain]);
            echo "POST /api/verifyotp -> status {$verifyRes['status']}\n";
            if ($verifyRes['status'] === 200) {
                echo "verifyotp response: " . ($verifyRes['content']) . "\n";
            } else {
                echo "verifyotp failed: {$verifyRes['content']}\n";
            }
        }
    }
}

// Login existing test user (test@example.com)
$loginRes = dispatch_request($kernel, 'POST', '/api/login', ['username' => $email, 'password' => 'secret']);
echo "POST /api/login -> status {$loginRes['status']}\n";
if ($loginRes['status'] === 200 && !empty($loginRes['json']['data']['token'])) {
    $token = $loginRes['json']['data']['token'];
    echo "Login succeeded, token length: " . strlen($token) . "\n";
    // Logout using token
    $logoutRes = dispatch_request($kernel, 'POST', '/api/logout', [], ['Authorization' => 'Bearer ' . $token]);
    echo "POST /api/logout -> status {$logoutRes['status']}\n";
    echo "Logout response: {$logoutRes['content']}\n";
} else {
    echo "Login failed: {$loginRes['content']}\n";
}

// 2) Web route controller tests (call controllers directly while Auth::login)
echo "\nWeb route controller tests (Searchtag, Delivery, Coupon):\n";

use Illuminate\Support\Facades\Auth as FAuth;
use App\Http\Controllers\SearchtagController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\CouponController;

// Ensure we are authenticated for controller calls (use web guard)
FAuth::guard('web')->loginUsingId($user->id);
echo "Authenticated as user id {$user->id}\n";

// Helper to call controller method with a request and print redirect status
function call_controller($controller, $method, $data = []) {
    $req = Request::create('/', 'POST', $data);
    $resp = $controller->$method($req);
    $status = null;
    if ($resp instanceof Illuminate\Http\RedirectResponse) {
        $status = 302;
    } else if ($resp instanceof Illuminate\Http\Response) {
        $status = $resp->getStatusCode();
    } else {
        $status = 200;
    }
    return ['status' => $status, 'response' => $resp];
}

// 2.a Searchtag: create (table uses manual id, create model directly)
$searchtagCtrl = new SearchtagController();
$title = 'stag-' . rand(1000,9999);
$maxId = \Illuminate\Support\Facades\DB::table('search_tags')->max('id') ?: 0;
$newId = $maxId + 1;
$tag = new App\Models\Searchtag();
$tag->id = $newId;
$tag->title = $title;
$tag->title_ar = null;
$tag->created_by = $user->id;
$tag->delete_status = 0;
$tag->count = 0;
$tag->save();
echo "Searchtag inserted id {$tag->id}\n";

// Find created tag
// $tag already available
if ($tag) {
    echo "Searchtag created id {$tag->id}\n";
    // update
    $r2 = call_controller($searchtagCtrl, 'update', ['editid' => $tag->id, 'title' => $title . '-u']);
    echo "Searchtag update -> status {$r2['status']}\n";
    // destroy
    $searchtagCtrl->destroy($tag, $tag->id);
    $tag2 = App\Models\Searchtag::find($tag->id);
    echo "Searchtag destroy -> delete_status = {$tag2->delete_status}\n";
} else {
    echo "Searchtag creation failed.\n";
}

// 2.b Delivery: create/update/destroy (create model directly)
$deliveryCtrl = new DeliveryController();
$dname = 'del-' . rand(1000,9999);
$maxId = \Illuminate\Support\Facades\DB::table('delivery_options')->max('id') ?: 0;
$newId = $maxId + 1;
$del = new App\Models\Delivery();
$del->id = $newId;
$del->name = $dname;
$del->name_ar = null;
$del->imageurl = null;
$del->created_by = $user->id;
$del->delete_status = 0;
$del->save();
echo "Delivery inserted id {$del->id}\n";
if ($del) {
    echo "Delivery created id {$del->id}\n";
    $r2 = call_controller($deliveryCtrl, 'update', ['editid' => $del->id, 'name' => $dname . '-u']);
    echo "Delivery update -> status {$r2['status']}\n";
    $deliveryCtrl->destroy($del, $del->id);
    $del2 = App\Models\Delivery::find($del->id);
    echo "Delivery destroy -> delete_status = {$del2->delete_status}\n";
} else {
    echo "Delivery creation failed.\n";
}

// 2.c Coupon: create/update/destroy (create model directly)
$couponCtrl = new CouponController();
$code = 'C' . rand(10000,99999);
$maxId = \Illuminate\Support\Facades\DB::table('coupons')->max('id') ?: 0;
$newId = $maxId + 1;
$coup = new App\Models\Coupon();
$coup->id = $newId;
$coup->coupon_type = 'All';
$coup->coupon_type_id = 0;
$coup->price_type = 'fixed';
$coup->coupon_val = 10;
$coup->coupon_code = $code;
$coup->coupon_code_ar = null;
$coup->created_by = $user->id;
$coup->delete_status = 0;
$coup->save();
echo "Coupon inserted id {$coup->id}\n";
if ($coup) {
    echo "Coupon created id {$coup->id}\n";
    $r2 = call_controller($couponCtrl, 'update', ['editid' => $coup->id, 'price_type' => 'fixed', 'coupon_val' => 20, 'coupon_code' => $code]);
    echo "Coupon update -> status {$r2['status']}\n";
    $couponCtrl->destroy($coup, $coup->id);
    $coup2 = App\Models\Coupon::find($coup->id);
    echo "Coupon destroy -> delete_status = {$coup2->delete_status}\n";
} else {
    echo "Coupon creation failed.\n";
}

echo "\nAutomated tests completed.\n";
