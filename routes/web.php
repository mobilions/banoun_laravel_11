<?php

use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CarousalController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\EmailtemplateController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageContentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductvariantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchtagController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\TopcollectionController;
use App\Http\Controllers\UsbannerController;
use App\Http\Controllers\VariantController;
use App\Http\Controllers\VariantsubController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/errorapi', [ApiAuthController::class, 'errorapi'])->name('errorapi');

// Explicit auth routes (replaced Auth::routes() to avoid laravel/ui dependency)
// Login
Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Registration
Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Password Reset
Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

// Email Verification
Route::get('email/verify', [App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/resend', [App\Http\Controllers\Auth\VerificationController::class, 'resend'])->name('verification.resend');

// Confirm Password
Route::get('password/confirm', [App\Http\Controllers\Auth\ConfirmPasswordController::class, 'showConfirmForm'])->name('password.confirm');
Route::post('password/confirm', [App\Http\Controllers\Auth\ConfirmPasswordController::class, 'confirm']);

Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [HomeController::class, 'index']);
Route::get('/admin', [HomeController::class, 'index'])->name('admin');

Route::prefix('category')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('category');
    Route::get('/create', [CategoryController::class, 'create'])->name('categorycreate');
    Route::post('/store', [CategoryController::class, 'store']);
    Route::get('/{id}/edit', [CategoryController::class, 'edit']);
    Route::post('/update', [CategoryController::class, 'update']);
    Route::post('/{id}/delete', [CategoryController::class, 'destroy']);
});

Route::prefix('brand')->group(function () {
    Route::get('/', [BrandController::class, 'index'])->name('brand');
    Route::get('/create', [BrandController::class, 'create'])->name('brandcreate');
    Route::post('/store', [BrandController::class, 'store']);
    Route::get('/{id}/edit', [BrandController::class, 'edit']);
    Route::post('/update', [BrandController::class, 'update']);
    Route::post('/{id}/delete', [BrandController::class, 'destroy']);
});

Route::prefix('subcategory')->group(function () {
    Route::get('/', [SubcategoryController::class, 'index'])->name('subcategory');
    Route::get('/create', [SubcategoryController::class, 'create'])->name('subcategorycreate');
    Route::post('/store', [SubcategoryController::class, 'store']);
    Route::get('/{id}/edit', [SubcategoryController::class, 'edit']);
    Route::post('/update', [SubcategoryController::class, 'update']);
    Route::post('/{id}/delete', [SubcategoryController::class, 'destroy']);
});

Route::prefix('banner')->group(function () {
    Route::get('/', [BannerController::class, 'index'])->name('banner');
    Route::get('/create', [BannerController::class, 'create'])->name('bannercreate');
    Route::post('/store', [BannerController::class, 'store']);
    Route::get('/{id}/edit', [BannerController::class, 'edit']);
    Route::post('/update', [BannerController::class, 'update']);
    Route::post('/{id}/delete', [BannerController::class, 'destroy']);
});

Route::prefix('topcollection')->group(function () {
    Route::get('/', [TopcollectionController::class, 'index'])->name('topcollection');
    Route::get('/create', [TopcollectionController::class, 'create'])->name('topcollectioncreate');
    Route::post('/store', [TopcollectionController::class, 'store']);
    Route::get('/{id}/edit', [TopcollectionController::class, 'edit']);
    Route::post('/update', [TopcollectionController::class, 'update']);
    Route::post('/{id}/delete', [TopcollectionController::class, 'destroy']);
});

Route::prefix('usbanner')->group(function () {
    Route::get('/', [UsbannerController::class, 'index'])->name('usbanner');
    Route::get('/create', [UsbannerController::class, 'create'])->name('usbannercreate');
    Route::post('/store', [UsbannerController::class, 'store']);
    Route::get('/{id}/edit', [UsbannerController::class, 'edit']);
    Route::post('/update', [UsbannerController::class, 'update']);
    Route::post('/{id}/delete', [UsbannerController::class, 'destroy']);
});

Route::prefix('carousal')->group(function () {
    Route::get('/', [CarousalController::class, 'index'])->name('carousal');
    Route::get('/create', [CarousalController::class, 'create'])->name('carousalcreate');
    Route::post('/store', [CarousalController::class, 'store']);
    Route::get('/{id}/edit', [CarousalController::class, 'edit']);
    Route::post('/update', [CarousalController::class, 'update']);
    Route::post('/{id}/delete', [CarousalController::class, 'destroy']);
});

Route::prefix('product')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products');
    Route::get('/create', [ProductController::class, 'create'])->name('productcreate');
    Route::post('/store', [ProductController::class, 'store']);
    Route::get('/{id}/edit', [ProductController::class, 'edit']);
    Route::post('/update', [ProductController::class, 'update']);
    Route::post('/{id}/delete', [ProductController::class, 'destroy']);
});

Route::get('/productsearch', [ProductController::class, 'productsearch'])->name('productsearch');
Route::post('/updateproductsearch', [ProductController::class, 'updateproductsearch']);
Route::post('/productvimage/store', [ProductController::class, 'productvimage']);
Route::post('/productvimage/{id}/delete', [ProductController::class, 'destroyproductvimage']);

Route::prefix('productvariants')->group(function () {
    Route::get('/{id}', [ProductvariantController::class, 'index'])->name('productvariants');
    Route::get('/create/{id}', [ProductvariantController::class, 'create'])->name('productvariantscreatenew');
    Route::post('/store', [ProductvariantController::class, 'store']);
    Route::get('/{id}/edit', [ProductvariantController::class, 'edit']);
    Route::post('/update', [ProductvariantController::class, 'update']);
    Route::post('/{id}/{product_id}/delete', [ProductvariantController::class, 'destroy']);
});

Route::prefix('variant')->group(function () {
    Route::get('/', [VariantController::class, 'index'])->name('variant');
    Route::get('/create', [VariantController::class, 'create'])->name('variantcreate');
    Route::post('/store', [VariantController::class, 'store']);
    Route::get('/{id}/edit', [VariantController::class, 'edit']);
    Route::post('/update', [VariantController::class, 'update']);
    Route::post('/{id}/delete', [VariantController::class, 'destroy']);
});

Route::prefix('variantsub')->group(function () {
    Route::get('/', [VariantsubController::class, 'index'])->name('variantsub');
    Route::get('/create', [VariantsubController::class, 'create'])->name('variantsubcreate');
    Route::post('/store', [VariantsubController::class, 'store']);
    Route::get('/{id}/edit', [VariantsubController::class, 'edit']);
    Route::post('/update', [VariantsubController::class, 'update']);
    Route::post('/{id}/delete', [VariantsubController::class, 'destroy']);
});

Route::prefix('pagecontent')->group(function () {
    Route::get('/', [PageContentController::class, 'index'])->name('pagecontent');
    Route::get('/{id}/edit', [PageContentController::class, 'edit']);
    Route::post('/update', [PageContentController::class, 'update']);
    Route::post('/{id}/delete', [PageContentController::class, 'destroy']);
});

Route::prefix('settings')->group(function () {
    Route::get('/', [SettingController::class, 'index'])->name('settings');
    Route::post('/store', [SettingController::class, 'store']);
});

Route::prefix('customer')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('customer');
    Route::get('/{id}/view', [CustomerController::class, 'view']);
    Route::get('/{id}/edit', [CustomerController::class, 'edit']);
    Route::post('/update', [CustomerController::class, 'update']);
    Route::post('/{id}/delete', [CustomerController::class, 'destroy']);
});

// Terms & Conditions Routes
Route::prefix('terms')->group(function () {
    Route::get('/', [FaqController::class, 'index'])->name('terms');
    Route::get('/create', [FaqController::class, 'create'])->name('termscreate');
    Route::post('/store', [FaqController::class, 'store'])->name('termsstore');
    Route::get('/{id}/edit', [FaqController::class, 'edit'])->name('termsedit');
    Route::post('/update', [FaqController::class, 'update'])->name('termsupdate');
    Route::post('/{id}/delete', [FaqController::class, 'destroy'])->name('termsdelete');
});

// FAQ Routes
Route::prefix('faq')->group(function () {
    Route::get('/', [FaqController::class, 'index'])->name('faq');
    Route::get('/create', [FaqController::class, 'create'])->name('faqcreate');
    Route::post('/store', [FaqController::class, 'store'])->name('faqstore');
    Route::get('/{id}/edit', [FaqController::class, 'edit'])->name('faqedit');
    Route::post('/update', [FaqController::class, 'update'])->name('faqupdate');
    Route::post('/{id}/delete', [FaqController::class, 'destroy'])->name('faqdelete');
});

// About Us Routes
Route::prefix('about')->group(function () {
    Route::get('/', [FaqController::class, 'index'])->name('about');
    Route::get('/create', [FaqController::class, 'create'])->name('aboutcreate');
    Route::post('/store', [FaqController::class, 'store'])->name('aboutstore');
    Route::get('/{id}/edit', [FaqController::class, 'edit'])->name('aboutedit');
    Route::post('/update', [FaqController::class, 'update'])->name('aboutupdate');
    Route::post('/{id}/delete', [FaqController::class, 'destroy'])->name('aboutdelete');
});

Route::prefix('searchtag')->group(function () {
    Route::get('/', [SearchtagController::class, 'index'])->name('searchtag');
    Route::get('/create', [SearchtagController::class, 'create'])->name('searchtagcreate');
    Route::post('/store', [SearchtagController::class, 'store']);
    Route::get('/{id}/edit', [SearchtagController::class, 'edit']);
    Route::post('/update', [SearchtagController::class, 'update']);
    Route::post('/{id}/delete', [SearchtagController::class, 'destroy']);
});

Route::get('/addsearchTag/{name}/{name_ar}', [SearchtagController::class, 'addsearchTag']);
Route::get('/selectBrandTag/{brandid}', [SearchtagController::class, 'selectBrandTag']);

Route::get('/addVariantValue/{variant_id}/{name}/{name_ar}/{color_val}', [VariantsubController::class, 'addVariantValue']);

Route::prefix('delivery')->group(function () {
    Route::get('/', [DeliveryController::class, 'index'])->name('delivery');
    Route::get('/create', [DeliveryController::class, 'create'])->name('deliverycreate');
    Route::post('/store', [DeliveryController::class, 'store']);
    Route::get('/{id}/edit', [DeliveryController::class, 'edit']);
    Route::post('/update', [DeliveryController::class, 'update']);
    Route::post('/{id}/delete', [DeliveryController::class, 'destroy']);
});

Route::prefix('stock')->group(function () {
    Route::get('/{variant_id}/{product_id}', [StockController::class, 'index'])->name('stock.variant');
    Route::post('/store', [StockController::class, 'store'])->name('stock.store');
    Route::post('/update', [StockController::class, 'update'])->name('stock.update');
    Route::get('/{id}/approve', [StockController::class, 'stcokapprove'])->name('stock.approve');
    Route::delete('/entry/{id}', [StockController::class, 'destroy'])->name('stock.destroy');
});

Route::get('/stocklist', [StockController::class, 'stocklist'])->name('stock.list');
Route::get('/stocklist/{id}/approve', [StockController::class, 'stcokapprovelist'])->name('stock.list.approve');
Route::get('/stocklog', [StockController::class, 'stocklog'])->name('stock.log');

Route::prefix('coupon')->group(function () {
    Route::get('/', [CouponController::class, 'index'])->name('coupon');
    Route::get('/create', [CouponController::class, 'create'])->name('couponcreate');
    Route::post('/store', [CouponController::class, 'store']);
    Route::get('/{id}/edit', [CouponController::class, 'edit']);
    Route::post('/update', [CouponController::class, 'update']);
    Route::post('/{id}/delete', [CouponController::class, 'destroy']);
});

Route::get('/users', [ProfileController::class, 'index'])->name('profile');
Route::get('/profile/create', [ProfileController::class, 'create'])->name('profilecreate');
Route::post('/profile/store', [ProfileController::class, 'store']);
Route::get('/user/{id}/edit', [ProfileController::class, 'edit']);
Route::post('/user/update', [ProfileController::class, 'update']);
Route::post('/user/{id}/delete', [ProfileController::class, 'destroy']);

Route::match(['get', 'post'], '/orders', [OrderController::class, 'index'])->name('order');
Route::get('/order/{id}/view', [OrderController::class, 'view'])->name('order.view');
Route::post('/orderstatus/{id}/{val}', [OrderController::class, 'updateorderstatus'])->name('order.status.update');

Route::get('/mailcontent', [HomeController::class, 'mailcontent']);

Route::prefix('emailtemplate')->group(function () {
    Route::get('/', [EmailtemplateController::class, 'index'])->name('emailtemplate');
    Route::get('/create', [EmailtemplateController::class, 'create'])->name('emailtemplatecreate');
    Route::post('/store', [EmailtemplateController::class, 'store']);
    Route::get('/{id}/edit', [EmailtemplateController::class, 'edit']);
    Route::post('/update', [EmailtemplateController::class, 'update']);
    Route::post('/{id}/delete', [EmailtemplateController::class, 'destroy']);
});

Route::match(['get', 'post'], '/reports/orders', [ReportController::class, 'order'])->name('order');
Route::get('/reports/stocklogs', [ReportController::class, 'stock']);

Route::get('/clearcache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('storage:link');

    return 'Cleared!';
});