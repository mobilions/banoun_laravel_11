<?php

use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\Api\HomepageController;
use App\Http\Controllers\Api\UserpageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('throttle:3,1')->group(function () {
    Route::post('login', [ApiAuthController::class, 'login']);
    Route::post('register', [ApiAuthController::class, 'register']);
// });


// Route::middleware('throttle:5,10')->group(function () {
    Route::post('verifyotp', [ApiAuthController::class, 'verifyotp']);
    Route::post('forgotpassword', [ApiAuthController::class, 'forgotpassword']);
// });

Route::post('updatepassword', [ApiAuthController::class, 'updatepassword']);
Route::post('changepassword', [ApiAuthController::class, 'changepassword']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('CheckApiAuthentication')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [ApiAuthController::class, 'logout']);
        Route::get('deleteuser', [ApiAuthController::class, 'deleteuser']);
        Route::post('updateimage', [ApiAuthController::class, 'updateimage']);
        Route::get('carousals', [ApiAuthController::class, 'carousals']);
        
        Route::post('sendphoneotp', [ApiAuthController::class, 'sendphoneotp']);
        Route::post('updatephone', [ApiAuthController::class, 'updatephone']);
        
        Route::post('sendemailotp', [ApiAuthController::class, 'sendemailotp']);
        Route::post('updateemail', [ApiAuthController::class, 'updateemail']);
        
        Route::get('getuser', [ApiAuthController::class, 'getuser']);
        Route::post('updatename', [ApiAuthController::class, 'updatename']);
        
        Route::post('/addresses', [HomepageController::class, 'addresses']);
        Route::post('/addtocart', [HomepageController::class, 'addtocart']);
        Route::post('/cartlists', [HomepageController::class, 'cartlists']);
        Route::post('/updatecart', [HomepageController::class, 'updatecart']);
        Route::post('/checkcoupon', [HomepageController::class, 'checkcoupon']);
        Route::post('/removecoupon', [HomepageController::class, 'removecoupon']);
        Route::post('/removecart', [HomepageController::class, 'removecart']);
        Route::post('/giftwrapupdate', [HomepageController::class, 'giftwrapupdate']);
        Route::post('/wishlists', [HomepageController::class, 'wishlists']);
        Route::post('/updatewishlist', [HomepageController::class, 'updatewishlist']);
        Route::post('/movetobag', [HomepageController::class, 'movetobag']);
        Route::post('/movealltobag', [HomepageController::class, 'movealltobag']);
        Route::post('/pricesummary', [HomepageController::class, 'pricesummary']);
        Route::post('/checkout', [HomepageController::class, 'checkout']);
        Route::post('/completeorder', [HomepageController::class, 'completeorder']);
        Route::post('/myorders', [HomepageController::class, 'myorders']);
        Route::post('/orderdetails', [HomepageController::class, 'orderdetails']);

        Route::post('/kids', [UserpageController::class, 'kids']);

    });
});

Route::get('/arealist', [HomepageController::class, 'arealist']);
Route::get('/homepage', [HomepageController::class, 'homepage']);
Route::get('/category', [HomepageController::class, 'index']);
Route::get('/categorylist', [HomepageController::class, 'categorylist']);
Route::post('/productlist', [HomepageController::class, 'productlist']);
Route::post('/productdetails', [HomepageController::class, 'productdetails']);
Route::post('/search', [HomepageController::class, 'topsearches']);
Route::post('/topsearches', [HomepageController::class, 'topsearches']);
Route::post('/productsizelist', [HomepageController::class, 'productsizelist']);
Route::get('/subcategories', [HomepageController::class, 'subcategories']);
Route::get('/brandlist', [HomepageController::class, 'brandlist']);
Route::get('/banners', [HomepageController::class, 'banners']);
Route::get('/topcollections', [HomepageController::class, 'topcollections']);
Route::get('/uspbanners', [HomepageController::class, 'usbanners']);
Route::get('/companydetails', [HomepageController::class, 'settings']);
Route::get('/faqs', [HomepageController::class, 'faqs']);
Route::get('/aboutus', [HomepageController::class, 'aboutus']);
Route::get('/searchcount', [HomepageController::class, 'searchcount']);
Route::post('/searchresults', [HomepageController::class, 'searchresults']);
Route::get('/sizecharts', [HomepageController::class, 'sizecharts']);
Route::get('/legal_terms', [HomepageController::class, 'legal_terms']);
Route::get('/terms_conditions', [HomepageController::class, 'terms_conditions']);