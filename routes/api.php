<?php

use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\Api\HomepageController;
use App\Http\Controllers\Api\UserpageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:3,1')->group(function () {
    Route::post('login', [ApiAuthController::class, 'login']);
    Route::post('register', [ApiAuthController::class, 'register']);
});

Route::middleware('throttle:5,10')->group(function () {
    Route::post('verifyotp', [ApiAuthController::class, 'verifyotp']);
    Route::post('forgotpassword', [ApiAuthController::class, 'forgotpassword']);
});

Route::post('updatepassword', [ApiAuthController::class, 'updatepassword']);
Route::post('changepassword', [ApiAuthController::class, 'changepassword']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [ApiAuthController::class, 'logout']);
    Route::get('carousals', [ApiAuthController::class, 'carousals']);
    
    Route::post('sendphoneotp', [ApiAuthController::class, 'sendphoneotp'])->middleware('throttle:5,10');
    Route::post('updatephone', [ApiAuthController::class, 'updatephone'])->middleware('throttle:5,10');
    
    Route::post('sendemailotp', [ApiAuthController::class, 'sendemailotp'])->middleware('throttle:5,10');
    Route::post('updateemail', [ApiAuthController::class, 'updateemail'])->middleware('throttle:5,10');
    
    Route::get('getuser', [ApiAuthController::class, 'getuser']);
    Route::post('updatename', [ApiAuthController::class, 'updatename']);
    
    Route::post('/addresses', [HomepageController::class, 'addresses']);
    Route::post('/kids', [UserpageController::class, 'kids']);
});

Route::get('/homepage', [HomepageController::class, 'homepage']);
Route::get('/category', [HomepageController::class, 'index']);
Route::get('/categorylist', [HomepageController::class, 'categorylist']);
Route::get('/productlist', [HomepageController::class, 'productlist']);
Route::get('/subcategories', [HomepageController::class, 'subcategories']);
Route::get('/brandlist', [HomepageController::class, 'brandlist']);
Route::get('/banners', [HomepageController::class, 'banners']);
Route::get('/topcollections', [HomepageController::class, 'topcollections']);
Route::get('/uspbanners', [HomepageController::class, 'usbanners']);
Route::get('/companydetails', [HomepageController::class, 'settings']);
Route::get('/faqs', [HomepageController::class, 'faqs']);
Route::get('/aboutus', [HomepageController::class, 'aboutus']);
Route::get('/searchcount', [HomepageController::class, 'searchcount']);