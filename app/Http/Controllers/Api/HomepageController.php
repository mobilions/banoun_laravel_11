<?php



namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Controllers\Api\BaseController as BaseController;

use DB;

use Validator;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Banner;
use App\Models\Topcollection;
use App\Models\Usbanner;
use App\Models\Setting;
use App\Models\Brand;
use App\Models\UserAddress;
use App\Models\ProductImage;
use App\Models\Area;
use App\Models\VariantsSub;
use App\Models\Productvariant;
use App\Models\Faq;
use App\Models\Cart;
use App\Models\CartMaster;
use App\Models\Wishlist;
use App\Models\SizeChart;
use App\Models\DeliveryOption;
use App\Models\UserKid;
use App\Models\User;




class HomepageController extends BaseController

{



    public function index()

    {

        $category = \App\Category::all();

        //print_r($category); exit();

        return \App\Http\Resources\Category::collection($category);

    }



    public function categorylist($limit="5")

    {

        if (!empty($_GET['limit'])) { $limit=$_GET['limit']; }

        if (!empty($_GET['lang']) && $_GET['lang'] == 'ar') {            

            $category = Category::select('id as categoryId','name_ar as name','description_ar as description','imageurl');

        }

        else{

            $category = Category::select('id as categoryId','name','description','imageurl');

        }

        $category = $category->where('delete_status', '0')->limit($limit)->get();



        if (!empty($category)) {
            $message["success"] = 'Category Lists';
            return $this->sendResponse($category, $message);

        } 

        else {

            return $this->sendError(['error'=>'No Category Available']);

        }

    }



    public function productlist(Request $request)

    {
        $colors = VariantsSub::select("id as colorId", "name", "color_val as code")->where("delete_status", "0")->get();
        $sizes = VariantsSub::select("id as sizeId", "name", "color_val as age")->where("delete_status", "0")->get();

        $limit = !empty($request->limit) ? (int)$request->limit : 10;
        $page  = !empty($request->page)  ? (int)$request->page  : 1;
        
        $offset = ($page - 1) * $limit;

        $lang = $request->lang ?? 'en';

        if ($lang === 'ar') {
            $nameField = 'name_ar as name';
            $descField = 'description_ar as description';
        } else {
            $nameField = 'name';
            $descField = 'description';
        }

        $product = Product::select(
                'products.id as productId',
                "products.$nameField",
                'products.price',
                'products.price_offer',
                "products.$descField",
                'products.category_id as categoryId',
                'products.subcategory_id as subcategoryId',
                'products.brand_id as brandId',
                'categories.name as categoryName',
                'subcategories.name as subcategoryName',
                'brands.name as brandName'
            )
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->leftJoin('subcategories', 'subcategories.id', '=', 'products.subcategory_id')
            ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
            ->where('products.delete_status', '0');
            if($request->keyId != ""){
                $product = $product->where("products.id", $request->keyId);
            }
            if ($request->keyword != "") {
                $product = $product->where("products.name", "like", "%" . $request->keyword . "%");
            }
            if($request->brandId != ""){
                $product = $product->where("products.brand_id", $request->brandId);
            }
            if($request->categoryId != ""){
                $product = $product->where("products.category_id", $request->categoryId);
            }
            if($request->subcategoryId != ""){
                $product = $product->where("products.subcategory_id", $request->subcategoryId);
            }
            $product = $product->offset($offset)
            ->limit($limit)
            ->get();

        $product = $product->map(function($item){
            $Productimage = ProductImage::where("product_id", $item->productId)->where("delete_status", "0")->first();
            $item->wishlistId = 0;
            $item->is_wishlisted = 0;
            $item->sizeid = 0;
            $item->sizeName = "";
            $item->imageurl = !empty($Productimage) ? $Productimage->imageurl : "";
            return $item;
        });

        $data['colors'] = $colors;
        $data['sizes'] = $sizes;
        $data['products'] = $product;

        $message["success"] = 'Product Lists';
        return $this->sendResponse($data, $message);

    }

    public function productdetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());
        }

        $product = Product::select(
                'products.id as productId',
                "products.name",
                'products.price',
                'products.price_offer',
                'products.category_id as categoryId',
                'products.subcategory_id as subcategoryId',
                'products.brand_id as brandId',
                'categories.name as categoryName',
                'subcategories.name as subcategoryName',
                'brands.name as brandName'
            )
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->leftJoin('subcategories', 'subcategories.id', '=', 'products.subcategory_id')
            ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
            ->where('products.id', $request->productId)
            ->first();

        $delivery = [];
        $product_info = [];
        $more_info = [];
        $similar_products = [];
        $images = [];
        if(!empty($product)){
            $Productimage = ProductImage::where("product_id", $product->productId)->where("delete_status", "0")->first();
            $images = ProductImage::where("product_id", $product->productId)->where("delete_status", "0")->pluck("imageurl")->toArray();
            $product->wishlistId = 0;
            $product->is_wishlisted = 0;
            $product->sizeid = 0;
            $product->qty = 0;
            $product->sizeName = "";
            $product->imageurl = !empty($Productimage) ? $Productimage->imageurl : "";
            
            $similar_products = Product::select(
                'products.id as productId',
                "products.name",
                'products.price',
                'products.price_offer',
                'products.category_id as categoryId',
                'products.subcategory_id as subcategoryId',
                'products.brand_id as brandId',
                'categories.name as categoryName',
                'subcategories.name as subcategoryName',
                'brands.name as brandName'
            )
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->leftJoin('subcategories', 'subcategories.id', '=', 'products.subcategory_id')
            ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
            ->where('products.subcategory_id', $product->subcategoryId)
            ->where('products.id', '!=', $request->productId)
            ->get();
            
            $similar_products = $similar_products->map(function($item){
                $Productimage = ProductImage::where("product_id", $item->productId)->where("delete_status", "0")->first();
                $item->wishlistId = 0;
                $item->is_wishlisted = 0;
                $item->sizeid = 0;
                $item->qty = 0;
                $item->sizeName = "";
                $item->imageurl = !empty($Productimage) ? $Productimage->imageurl : "";

                return $item;
            });

            $delivery = DeliveryOption::select("name", "icon")->where("delete_status", "0")->get();

            $productTemp = Product::where("id", $product->productId)->first();
            $product_info["description"] = $productTemp->description;
            $more_info["more_info"] = $productTemp->more_info;
        }

        $data['product'] = $product;
        $data['delivery'] = $delivery;
        $data['product_info'] = $product_info;
        $data['more_info'] = $more_info;
        $data['similar_products'] = $similar_products;
        $data['images'] = $images;

        $message["success"] = 'Product detail get successfully.';
        return $this->sendResponse($data, $message);

    }

    public function topsearches(Request $request)
    {

        $lang = $request->lang ?? 'en';

        if ($lang === 'ar') {
            $nameField = 'name_ar as name';
        } else {
            $nameField = 'name';
        }

        $product = Product::select(
                'products.id as productId',
                "products.$nameField",
                'products.price',
                'products.price_offer',
                'products.category_id as categoryId',
                'products.subcategory_id as subcategoryId',
                'products.brand_id as brandId',
                'categories.name as categoryName',
                'subcategories.name as subcategoryName',
                'brands.name as brandName'
            )
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->leftJoin('subcategories', 'subcategories.id', '=', 'products.subcategory_id')
            ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
            ->where('products.delete_status', '0')
            ->orderBy("products.search_count", "desc")
            ->get();

        $product = $product->map(function($item){
            $Productimage = ProductImage::where("product_id", $item->productId)->where("delete_status", "0")->first();
            $item->wishlistId = 0;
            $item->is_wishlisted = 0;
            $item->sizeid = 0;
            $item->qty = 0;
            $item->sizeName = "";
            $item->imageurl = !empty($Productimage) ? $Productimage->imageurl : "";
            return $item;
        });

        $data['top_searches'] = $product;

        $message["success"] = 'Top searches list get successfully.';
        return $this->sendResponse($data, $message);
    }

    public function productsizelist(Request $request)
    {
        $ProductSize = Productvariant::select(
                'productvariants.product_id as productId',
                'productvariants.size_id as sizeid',
                'productvariants.price',
                'productvariants.available_quantity',
                'variants_sub.name',
            )
            ->leftJoin('variants_sub', 'variants_sub.id', '=', 'productvariants.size_id')
            ->where('productvariants.delete_status', '0')
            ->where('variants_sub.delete_status', '0');
            if($request->productId != ""){
                $ProductSize = $ProductSize->where("productvariants.product_id", $request->productId);    
            }
            $ProductSize = $ProductSize->get();

        $data['productsize'] = $ProductSize;

        $message["success"] = 'Product size list get successfully.';
        return $this->sendResponse($data, $message);
    }


    public function homepage(){

        $topbanners = Topcollection::select('imageurl', 'type', 'category_id as categoryId')->where('delete_status','0')->take(4)->get();
        $categories = Category::select('id as categoryId', 'name', 'description', 'imageurl')->where('delete_status','0')->take(4)->get();
        $designers = Brand::select('id as brandId', 'imageurl', 'name')->where('delete_status','0')->take(4)->get();
        $new_arrivals = Product::select('id as productId', 'name', 'price', 'price_offer', 'category_id as categoryId', 'subcategory_id as subcategoryId', 'brand_id as brandId')->where('delete_status','0')->take(8)->get();

        $new_arrivals = $new_arrivals->map(function($item){
            $Productimage = ProductImage::where("product_id", $item->productId)->where("delete_status", "0")->first();
            $item->wishlistId = 0;
            $item->brandName = "";
            $item->categoryName = "";
            $item->subcategoryName = "";
            $item->is_wishlisted = 0;
            $item->sizeid = 0;
            $item->qty = 0;
            $item->sizeName = "";
            $item->imageurl = !empty($Productimage) ? $Productimage->imageurl : "";

            return $item;
        });

        $trending = Product::select('id as productId', 'name', 'price', 'price_offer',  'category_id as categoryId', 'subcategory_id as subcategoryId', 'brand_id as brandId')->where('delete_status','0')->inRandomOrder()->limit(8)->get();

        $trending = $trending->map(function($item){
            $Productimage = ProductImage::where("product_id", $item->productId)->where("delete_status", "0")->first();
            $item->wishlistId = 0;
            $item->brandName = "";
            $item->categoryName = "";
            $item->subcategoryName = "";
            $item->is_wishlisted = 0;
            $item->sizeid = 0;
            $item->qty = 0;
            $item->sizeName = "";
            $item->imageurl = !empty($Productimage) ? $Productimage->imageurl : "";

            return $item;
        });

        $data["header"] = "Free delivery for all over above 15 KD";
        $data["cart_count"] = 0;
        $data["topbanners"] = $topbanners;
        $data["categories"] = $categories;
        $data["designers"] = $designers;
        $data["new_arrivals"] = $new_arrivals;
        $data["trending"] = $trending;

        $message["success"] = "Home page detail get successfully.";
        return $this->sendResponse($data, $message);
    }


    public function subcategories($id=0)

    {

        if (!empty($_GET['categoryid'])) { $id=$_GET['categoryid']; }
        echo $id;
        if (!empty($_GET['lang']) && $_GET['lang'] == 'ar') {            

            $subcategory = Subcategory::select('id as subcategoryId','name_ar as name','description_ar as description','imageurl','category_id as categoryId');

        }

        else{

            $subcategory = Subcategory::select('id as subcategoryId','name','description','imageurl','category_id as categoryId');

        }

        if($id != ""){
            $subcategory = $subcategory->where('category_id', $id);
        }
        $subcategory = $subcategory->where('delete_status', '0')->get();



        if (!empty($subcategory)) {
            $message["success"] = 'SubCategory Lists';
            return $this->sendResponse($subcategory, $message);

        } 

        else {

            return $this->sendError(['error'=>'No SubCategory Available']);

        }

    }

    public function brandlist()
    {

        if (!empty($_GET['lang']) && $_GET['lang'] == 'ar') {            
            $Brand = Brand::select('id as brandId','imageurl','name_ar as name');
        } else {
            $Brand = Brand::select('id as brandId','imageurl','name');

        }

        $Brand = $Brand->where('delete_status', '0')->get();

        if (!empty($Brand)) {
            $message["success"] = 'Brand Lists';
            return $this->sendResponse($Brand, $message);
        } else {
            return $this->sendError(['error'=>'No Brand Available']);
        }
    }



    public function banners()

    {

        $banner = Banner::select('imageurl','order_id')->where('delete_status', '0')->get();



        if (!empty($banner)) {

            return response()->json([

              "success"=>true,

              "data"=>$banner

            ], 200);

        } 

        else {

            return response()->json([

              "success"=>false,

              "message" => "Category not found"

            ], 200);

        }

    }

    public function searchcount(Request $request)
    {
        $Product = Product::where('id', $request->productId)->first();
        if(!empty($Product)){
            $Product->search_count = $Product->search_count + 1;
            $Product->save();
        }
        $message["success"] = "Search count updated!";
        return response()->json([
            "success"=>true,
            "message"=>$message,
        ], 200);
    }

    public function searchresults(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'keyword' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());
        }

        $Products = Product::select("id as keyId", "name as keyword")->where('name', "LIKE", "%". $request->keyword ."%")->where("delete_status", "0")->get();

        $message['success'] = "Search result get successfully.";
        return $this->sendResponse($Products, $message); 
    }



    public function topcollections()

    {

        if (Topcollection::where('delete_status', '0')->exists()) {

            $category = Topcollection::select('name','name_ar','description','description_ar','imageurl','category_id as categoryId')->where('delete_status', '0')->get()->toJson(JSON_PRETTY_PRINT);

            return response($category, 200);

        } 

        else {

            return response()->json([

              "message" => "Topcollections not found"

            ], 200);

        }

    }



    public function usbanners()

    {

        if (Usbanner::where('delete_status', '0')->exists()) {

            $category = Usbanner::select('name','name_ar','description','description_ar','imageurl','shopby','category_id as categoryId')->where('delete_status', '0')->get()->toJson(JSON_PRETTY_PRINT);

            return response($category, 200);

        } 

        else {

            return response()->json([

              "message" => "usbanners not found"

            ], 200);

        }

    }



    public function settings()

    {

        if (!empty($_GET['lang']) && $_GET['lang'] == 'ar') {     

        $settings = Setting::select('company_ar as company','description_ar as description','header_ar as header','contact_person','phone','email','support_phone','support_email','location','imageurl','facebook','twitter','instagram','whatsapp','google');

        }

        else{

        $settings = Setting::select('company','description','header','contact_person','phone','email','support_phone','support_email','location','imageurl','facebook','twitter','instagram','whatsapp','google');

        }

        $settings = $settings->where('delete_status', '0')->first();



        if (!empty($settings)) {

            return response()->json([

              "success"=>true,

              "data"=>$settings

            ], 200);

        } 

        else {

            return response()->json([

              "success"=>false,

              "message" => "Company details not found"

            ], 200);

        }

    }



    public function faqs()
    {
        if (!empty($_GET['lang']) && $_GET['lang'] == 'ar') {   
            $details = DB::table('qa_details')->select('title_ar as title','content_ar as content')->where('type', 'faq')->where('delete_status', '0')->get();
        } else {
            $details = DB::table('qa_details')->select('title','content')->where('type', 'faq')->where('delete_status', '0')->get();
        }

        if (count($details)>0) { 
            $message["success"] = "FAQ Details";
            return $this->sendResponse($details, $message); 
        } else { 
            return $this->sendError(['error'=>'No FAQ Details']); 
        }

    }



    public function aboutus()

    {

        if (!empty($_GET['lang']) && $_GET['lang'] == 'ar') {   

            $details = DB::table('qa_details')->select('title_ar as title','content_ar as content')->where('type', 'about')->where('delete_status', '0')->get();

        }

        else{

            $details = DB::table('qa_details')->select('title','content')->where('type', 'about')->where('delete_status', '0')->get();

        }



        if (count($details)>0) { 
            $message["success"] = "About Details";
            return $this->sendResponse($details, $message); 

        } 

        else { 

            return $this->sendError(['error'=>'No About Details']); 

        }

    }

    public function arealist(){
        $Areas = Area::select("id as areaId", "name")->where("delete_status", "0")->get();
        
        $message['success'] = "Area list get successfully.";
        return $this->sendResponse($Areas, $message); 
    }

    public function addresses(Request $request){

        if($request->action == "create" || $request->action == "update"){
            if($request->is_default == "1"){
                UserAddress::where("user_id", auth("api")->user()->id)->update([
                    "is_default" => 0
                ]);
            }

            $UserAddressArr = [
                "user_id" => auth("api")->user()->id,
                "name" => $request->name,
                "country_mobile" => $request->country_mobile,
                "mobile" => $request->mobile,
                "landline" => $request->landline,
                "country_landline" => $request->country_landline,
                "type" => $request->type,
                "area_id" => $request->area_id,
                "block" => $request->block,
                "street" => $request->street,
                "avenue" => $request->avenue,
                "building" => $request->building,
                "floor" => $request->floor,
                "apartment" => $request->apartment,
                "additional_info" => $request->additional_info,
                "latitude" => $request->latitude,
                "longitude" => $request->longitude,
                "is_default" => $request->is_default,
            ];

            if($request->action == "create"){
                UserAddress::create($UserAddressArr);
            } else {
                UserAddress::where("id", $request->addressId)->update($UserAddressArr);
            }
        }

        if($request->action == "default"){
            UserAddress::where("user_id", auth("api")->user()->id)->update([
                "is_default" => 0
            ]);
            
            UserAddress::where("id", $request->addressId)->update([
                "is_default" => 1
            ]);
        }

        if($request->action == "delete"){
            UserAddress::where("id", $request->addressId)->update([
                "delete_status" => 1
            ]);
        }

        $UserAddresses = UserAddress::select("user_addresses.id as addressId", "user_addresses.name", "user_addresses.country_mobile", "user_addresses.mobile", "user_addresses.country_landline", "user_addresses.landline", "user_addresses.area_id", "user_addresses.type", "user_addresses.block", "user_addresses.street", "user_addresses.avenue", "user_addresses.building", "user_addresses.floor", "user_addresses.apartment", "user_addresses.additional_info", "user_addresses.latitude", "user_addresses.longitude", "user_addresses.is_default", "areas.name as area")->leftJoin("areas", "areas.id", "=", "user_addresses.area_id")->where("user_addresses.user_id", auth("api")->user()->id)->where("user_addresses.delete_status", "0")->get();
        
        $message['success'] = "Addresses list.";
        return $this->sendResponse($UserAddresses, $message); 
    }

    public function addtocart(Request $request){
        $validator = Validator::make($request->all(), [
            'productId' => 'required|integer',
            'sizeid'    => 'required|integer',
            'qty'       => 'required|integer|min:1',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());
        }

        $userId = auth()->id();
        $product = Product::where("id", $request->productId)->first();

        if (empty($product)) {
            return $this->sendError(["error" => "Product not found"]);
        }

        $size = Productvariant::where('product_id', $request->productId)
            ->where('id', $request->sizeid)
            ->first();

        if (empty($size)) {
            return $this->sendError(["error" => "Size not available"]);
        }

        if ($size->available_quantity < $request->qty) {
            return $this->sendError(["error" => "Only ".$size->available_quantity." quantity available"]);
        }

        $cart = Cart::where('user_id', $userId)
            ->where('product_id', $request->productId)
            ->where('size_id', $request->sizeid)
            ->where('delete_status', "0")
            ->first();

        if ($cart) {
            $cart->qty += $request->qty;
            $cart->save();
        } else {
            $Productvariant = Productvariant::where("product_id", $request->productId)->where("size_id", $request->sizeid)->where("delete_status", "0")->first();
            $cart = Cart::create([
                'user_id'     => $userId,
                'product_id'  => $request->productId,
                'size_id'     => $request->sizeid,
                'variant_id'     => !empty($Productvariant) ? $Productvariant->id : null,
                'qty'         => $request->qty,
                'actual_price'       => $size->price,
                'offer_price' => $size->price,
                'total_price' => $size->price * $request->qty,
            ]);
        }
        $cartData = $this->getCartSummary($userId);

        $message['success'] = "Added to cart successfully.";
        return $this->sendResponse($cartData, $message); 
    }

    public function updatecart(Request $request){
        $validator = Validator::make($request->all(), [
            'cartId' => 'required|integer',
            'qty'       => 'required|integer|min:1',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());
        }

        $userId = auth()->id();
        $cart = Cart::where("id", $request->cartId)->first();

        if (empty($cart)) {
            return $this->sendError(["error" => "Cart item not found"]);
        }

        $size = Productvariant::where('product_id', $cart->product_id)
            ->where('id', $cart->size_id)
            ->first();

        if ($size->available_quantity < $request->qty) {
            return $this->sendError(["error" => "Only ".$size->available_quantity." quantity available"]);
        }

        $cart->qty = $request->qty;
        $cart->save();
        
        $cartData = $this->getCartSummary($userId);

        $message['success'] = "Cart updated successfully.";
        return $this->sendResponse($cartData, $message); 
    }

    public function cartlists(){
        $userId = auth()->id();
        $cartData = $this->getCartSummary($userId);

        $message['success'] = "Cart list get successfully.";
        return $this->sendResponse($cartData, $message); 
    }

    public function checkcoupon(Request $request){
        $validator = Validator::make($request->all(), [
            'couponId' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());
        }
        
        $userId = auth()->id();
        $CartMaster = CartMaster::where("user_id", $userId)->where("is_checkouted", "0")->where("is_deleted", "0")->first();
        if(empty($CartMaster)){
            return $this->sendError(["error" => "Cart is empty."]);
        }
        $CartMaster->update([
            "coupon_code" => $request->couponId
        ]);

        $cartData = $this->getCartSummary($userId);
        if(!empty($cartData)){
            unset($cartData["carts"]);
        }

        $message['success'] = "Coupon apply successfully.";
        return $this->sendResponse($cartData, $message); 
    }

    public function removecoupon(Request $request){
        $userId = auth()->id();
        $CartMaster = CartMaster::where("user_id", $userId)->where("is_checkouted", "0")->where("is_deleted", "0")->first();
        if(empty($CartMaster)){
            return $this->sendError(["error" => "Cart is empty."]);
        }
        $CartMaster->update([
            "coupon_code" => null
        ]);

        $cartData = $this->getCartSummary($userId);
        if(!empty($cartData)){
            unset($cartData["carts"]);
        }

        $message['success'] = "Coupon removed successfully.";
        return $this->sendResponse($cartData, $message); 
    }

    public function removecart(Request $request){
        $validator = Validator::make($request->all(), [
            'cartId' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());
        }

        $userId = auth()->id();
        $Cart = Cart::where("id", $request->cartId)->first();
        if($request->action == "movetowishlist" && !empty($Cart)){
            Wishlist::create([
                "product_id" => $Cart->product_id,
                "variant_id" => $Cart->variant_id,
                "size_id" => $Cart->size_id,
                "qty" => $Cart->qty,
                "created_by" => $Cart->user_id,
            ]);
        }
        Cart::where("id", $request->cartId)->delete();
        
        $cartData = $this->getCartSummary($userId);

        $message['success'] = "Cart removed successfully.";
        return $this->sendResponse($cartData, $message); 
    }

    public function movetobag(Request $request){
        $validator = Validator::make($request->all(), [
            'wishlistId' => 'required',
            'sizeid' => 'required',
            'qty' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());
        }

        $userId = auth()->id();
        $Wishlist = Wishlist::where("id", $request->wishlistId)->first();
        if(empty($Wishlist)){
            return $this->sendError(["error" => "Wishlist not found."]);
        }
        $Product = Product::where("id", $Wishlist->product_id)->first();
        if(empty($Product)){
            return $this->sendError(["error" => "Product not found."]);
        }

        Cart::create([
            "user_id" => $userId,
            "product_id" => $Wishlist->product_id,
            "variant_id" => $Wishlist->variant_id,
            "size_id" => $request->sizeid,
            "qty" => $request->qty,
            "actual_price" => $Product->price,
            "offer_price" => $Product->price_offer,
            "total_price" => $Product->price * $request->qty,
        ]);

        $Wishlist->update([
            "delete_status" => "1"
        ]);

        $data = $this->getWishlistSummary($userId);

        $message['success'] = "Item move to cart list successfully.";
        return $this->sendResponse($data, $message); 
    }

    public function movealltobag(Request $request){
        $userId = auth()->id();
        $Wishlists = Wishlist::where("created_by", $userId)->where("delete_status", "0")->get();
        foreach($Wishlists as $Wishlist){
            $Product = Product::where("id", $Wishlist->product_id)->first();
    
            Cart::create([
                "user_id" => $userId,
                "product_id" => $Wishlist->product_id,
                "variant_id" => $Wishlist->variant_id,
                "size_id" => $Wishlist->size_id,
                "qty" => $Wishlist->qty,
                "actual_price" => $Product ? $Product->price : 0,
                "offer_price" => $Product ? $Product->price_offer : 0,
                "total_price" => ($Product ? $Product->price : 0) * $Wishlist->qty,
            ]);
    
            $Wishlist->update([
                "delete_status" => "1"
            ]);
        }
        $cart_count = Cart::where("user_id", $userId)->where("delete_status", "0")->count();
        $data['cart_count'] = $cart_count;

        $message['success'] = "All item move to cart list successfully.";
        return $this->sendResponse($data, $message); 
    }

    public function wishlists(Request $request){

        $userId = auth()->id();
        $message_res = "Wishlist get successfully.";
        if($request->action == "add"){
            Wishlist::create([
                "product_id" => $request->productId,
                "created_by" => $userId,
            ]);
            $message_res = "Wishlist created successfully.";
        }
        if($request->action == "remove"){
            Wishlist::where("product_id", $request->productId)->where("created_by", $userId)->update(["delete_status" => "1"]);
            $message_res = "Wishlist removed successfully.";
        }
        
        $data = $this->getWishlistSummary($userId);

        $message['success'] = $message_res;
        return $this->sendResponse($data, $message); 
    }
    
    public function updatewishlist(Request $request){
        $userId = auth()->id();
        Wishlist::where("product_id", $request->productId)->where("created_by", $userId)->where("delete_status", "0")->update(["size_id" => $request->sizeid, "qty" => $request->qty]);
        $message_res = "Wishlist updated successfully.";
        
        $data = $this->getWishlistSummary($userId);

        $message['success'] = $message_res;
        return $this->sendResponse($data, $message); 
    }

    public function giftwrapupdate(Request $request){
        $validator = Validator::make($request->all(), [
            'is_giftwrap' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());
        }

        $userId = auth()->id();
        $CartMaster = CartMaster::where("user_id", $userId)->where("is_checkouted", "0")->where("is_deleted", "0")->first();
        if(empty($CartMaster)){
            return $this->sendError(["error" => "Cart is empty."]);
        }
        $CartMaster->update([
            "is_giftwrap" => $request->is_giftwrap
        ]);

        $cartData = $this->getCartSummary($userId);
        if(!empty($cartData)){
            unset($cartData["carts"]);
        }

        $message['success'] = "Gift wrap is updated successfully.";
        return $this->sendResponse($cartData, $message); 
    }

    private function getCartSummary($userId){
        $carts = Cart::select(
                "carts.id as cartId",
                "carts.qty",
                "carts.actual_price as price",
                "carts.product_id as productId",
                "products.name as productName",
                "products.price as productPrice",
                "carts.offer_price as price_offer",
                "variants_sub.name as sizeName",
                "productvariants.available_quantity"
            )
            ->where("carts.user_id", $userId)
            ->where("carts.delete_status", "0")
            ->leftJoin("products", "products.id", "=", "carts.product_id")
            ->leftJoin("productvariants", "productvariants.id", "=", "carts.variant_id")
            ->leftJoin("variants_sub", "variants_sub.id", "=", "productvariants.size_id")
            ->get();

        $total = 0;
        $subtotal = 0;
        $tax = 0;
        $delivery = 0;
        $discount = 0;
        $grandtotal = 0;
        $totalqty = 0;
        $cart_count = 0;
        $carts = $carts->map(function($item) use (&$total, &$subtotal, &$tax, &$delivery, &$discount, &$grandtotal, &$totalqty, &$cart_count) {
            $Productimage = ProductImage::where("product_id", $item->productId)->where("delete_status", "0")->first();
            
            $item->available_quantity = ($item->available_quantity == null || $item->available_quantity == "") ? "0" : $item->available_quantity;
            $item->imageurl = !empty($Productimage) ? $Productimage->imageurl : "";

            $itemTotal = $item->price * $item->qty;
            $subtotal += $itemTotal;
            $totalqty += $item->qty;

            if (!empty($item->tax)) {
                $tax += 0;
            }

            $cart_count += 1;
            return $item;
        });
        
        $total = $subtotal + $tax - $discount;
        $grandtotal = $total + $delivery;

        $CartMaster = CartMaster::where("user_id", $userId)->where("is_checkouted", "0")->where("is_deleted", "0")->first();
        if(empty($CartMaster)){
            CartMaster::create([
                "order_number" => "T". $userId . time(),
                "user_id" => $userId,
                "total" => $total,
                "subtotal" => $subtotal,
                "tax" => $tax,
                "delivery" => $delivery,
                "discount" => $discount,
                "grandtotal" => $grandtotal,
                "totalqty" => $totalqty,
            ]);
        } else {
            CartMaster::where("id", $CartMaster->id)->update([
                "total" => $total,
                "subtotal" => $subtotal,
                "tax" => $tax,
                "delivery" => $delivery,
                "discount" => $discount,
                "grandtotal" => $grandtotal,
                "totalqty" => $totalqty,
            ]);
        }
        $CartMaster = CartMaster::where("user_id", $userId)->where("is_checkouted", "0")->where("is_deleted", "0")->first();

        $data = [
            "carts" => $carts,
            "coupon_code" => $CartMaster->coupon_code,
            "is_giftwrap" => $CartMaster->is_giftwrap,
            "giftwrap_price" => $CartMaster->giftwrap_price,
            "totalprice" => $CartMaster->total,
            "cart_count" => $cart_count,
            "offer_price" => $CartMaster->total,
            "delivery_price" => $CartMaster->delivery,
            "promo_price" => $CartMaster->promo_price,
            "grand_total" => $CartMaster->grandtotal,
        ];

        return $data;
    }

    private function getWishlistSummary($userId){
        $cart_count = Cart::where("user_id", $userId)->where("delete_status", "0")->count();

        $Wishlists = Wishlist::select("wishlists.id as wishlistId", "wishlists.product_id as productId", "products.name", "products.price", "products.price_offer", "products.category_id as categoryId", "products.subcategory_id as subcategoryId", "products.brand_id as brandId", "brands.name as brandName", "categories.name as categoryName", "subcategories.name as subcategoryName", "wishlists.size_id as sizeid", "wishlists.qty", "variants_sub.name as sizeName")
        ->leftJoin("products", "products.id", "=", "wishlists.product_id")
        ->leftJoin("brands", "brands.id", "=", "products.brand_id")
        ->leftJoin("categories", "categories.id", "=", "products.category_id")
        ->leftJoin("subcategories", "subcategories.id", "=", "products.subcategory_id")
        ->leftJoin("variants_sub", "variants_sub.id", "=", "wishlists.size_id")
        ->where("wishlists.created_by", $userId)
        ->where("wishlists.delete_status", "0")
        ->get();

        $Wishlists = $Wishlists->map(function($item){
            $Productimage = ProductImage::where("product_id", $item->productId)->where("delete_status", "0")->first();
            
            $item->imageurl = !empty($Productimage) ? $Productimage->imageurl : "";
            $item->is_wishlisted = 1;
            return $item;
        });

        $data['cart_count'] = $cart_count;
        $data['list'] = $Wishlists;

        return $data;
    }

    public function pricesummary(Request $request){
        $validator = Validator::make($request->all(), [
            'addressId' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());
        }

        $userId = auth()->id();
        $CartMaster = CartMaster::where("user_id", $userId)->where("is_checkouted", "0")->where("is_deleted", "0")->first();
        if(empty($CartMaster)){
            return $this->sendError(["error" => "Cart is empty."]);
        }
        
        $UserAddresses = UserAddress::select("user_addresses.id as addressId", "user_addresses.name", "user_addresses.country_mobile", "user_addresses.mobile", "user_addresses.country_landline", "user_addresses.landline", "user_addresses.area_id", "user_addresses.type", "user_addresses.block", "user_addresses.street", "user_addresses.avenue", "user_addresses.building", "user_addresses.floor", "user_addresses.apartment", "user_addresses.additional_info", "user_addresses.latitude", "user_addresses.longitude", "user_addresses.is_default", "areas.name as area")->leftJoin("areas", "areas.id", "=", "user_addresses.area_id")->where("user_addresses.id", $request->addressId)->first();

        $cart_count = Cart::where("user_id", $userId)->where("delete_status", "0")->count();
        $data = [
            "coupon_code" => $CartMaster->coupon_code,
            "is_giftwrap" => $CartMaster->is_giftwrap,
            "use_credit" => $CartMaster->use_credit,
            "giftwrap_price" => $CartMaster->giftwrap_price,
            "credit_price" => $CartMaster->credit_price,
            "credit_balance" => "",
            "cart_total" => $CartMaster->subtotal,
            "totalqty" => $CartMaster->totalqty,
            "cart_count" => $cart_count,
            "orderId" => $CartMaster->id,
            "totalprice" => $CartMaster->total,
            "offer_price" => $CartMaster->total,
            "promo_price" => $CartMaster->promo_price,
            "delivery_price" => $CartMaster->delivery,
            "grand_total" => $CartMaster->grandtotal,
        ];
        $data['address'] = $UserAddresses;

        $message['success'] = "Price summary get successfully.";
        return $this->sendResponse($data, $message); 
    }

    public function checkout(Request $request){
        $validator = Validator::make($request->all(), [
            'addressId' => 'required',
            'paymentType' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());
        }

        $userId = auth()->id();
        $CartMaster = CartMaster::where("user_id", $userId)->where("is_checkouted", "0")->where("is_deleted", "0")->first();
        if(empty($CartMaster)){
            return $this->sendError(["error" => "Cart is empty."]);
        }
        
        $CartMaster->update([
            "address_id" => $request->addressId,
            "paymenttype" => $request->paymentType,
            "use_credit" => $request->useCredit,
            "paymentstatus" => "pending",
            "giftwrap_msg" => $request->giftwrap_msg,
        ]);

        $UserAddresses = UserAddress::select("user_addresses.id as addressId", "user_addresses.name", "user_addresses.country_mobile", "user_addresses.mobile", "user_addresses.country_landline", "user_addresses.landline", "user_addresses.area_id", "user_addresses.type", "user_addresses.block", "user_addresses.street", "user_addresses.avenue", "user_addresses.building", "user_addresses.floor", "user_addresses.apartment", "user_addresses.additional_info", "user_addresses.latitude", "user_addresses.longitude", "user_addresses.is_default", "areas.name as area")->leftJoin("areas", "areas.id", "=", "user_addresses.area_id")->where("user_addresses.id", $request->addressId)->first();

        $cart_count = Cart::where("user_id", $userId)->where("delete_status", "0")->count();
        $data = [
            "coupon_code" => $CartMaster->coupon_code,
            "is_giftwrap" => $CartMaster->is_giftwrap,
            "use_credit" => $CartMaster->use_credit,
            "giftwrap_price" => $CartMaster->giftwrap_price,
            "credit_price" => $CartMaster->credit_price,
            "credit_balance" => "",
            "cart_total" => $CartMaster->subtotal,
            "totalqty" => $CartMaster->totalqty,
            "cart_count" => $cart_count,
            "orderId" => $CartMaster->id,
            "totalprice" => $CartMaster->total,
            "offer_price" => $CartMaster->total,
            "promo_price" => $CartMaster->promo_price,
            "delivery_price" => $CartMaster->delivery,
            "grand_total" => $CartMaster->grandtotal,
        ];
        $data['address'] = $UserAddresses;

        $message['success'] = "Checkout successfully.";
        return $this->sendResponse($data, $message); 
    }

    public function completeorder(Request $request){
        $validator = Validator::make($request->all(), [
            'orderId' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());
        }

        $userId = auth()->id();
        $CartMaster = CartMaster::where("id", $request->orderId)->first();
        if(empty($CartMaster)){
            return $this->sendError(["error" => "Cart is empty."]);
        }
        
        $CartMaster->update([
            "referenceId" => $request->referenceId,
            "TranID" => $request->TranID,
            "TrackID" => $request->TrackID,
            "PaymentID" => $request->PaymentID,
            "orderstatus" => $request->Status,
            "is_checkouted" => 1,
            "created_at" => date("Y-m-d H:i:s"),
        ]);

        Cart::where("user_id", $userId)->where("delete_status", "0")->update([
            "delete_status" => "1",
            "master_id" => $CartMaster->id
        ]);

        $data['order'] = [
            "orderId" => $CartMaster->id,
            "order_date" => date("d-m-Y", strtotime($CartMaster->created_at)),
            "order_number" => $CartMaster->order_number,
            "grand_total" => $CartMaster->grandtotal,
        ];

        $message['success'] = "Order completed successfully.";
        return $this->sendResponse($data, $message); 
    }

    public function myorders(Request $request){
        
        $userId = auth()->id();
        $page  = request()->get('page', 1);
        $limit = request()->get('limit', 10);
        $offset = ($page - 1) * $limit;

        $CartMasters = CartMaster::where('user_id', $userId)
            ->where('is_checkouted', '1')
            ->skip($offset)
            ->take($limit)
            ->get();

        $MyOrdersData = [];
        foreach($CartMasters as $CartMaster){
            $UserAddresse = UserAddress::select("user_addresses.id as addressId", "user_addresses.name", "user_addresses.country_mobile", "user_addresses.mobile", "user_addresses.country_landline", "user_addresses.landline", "user_addresses.area_id", "user_addresses.type", "user_addresses.block", "user_addresses.street", "user_addresses.avenue", "user_addresses.building", "user_addresses.floor", "user_addresses.apartment", "user_addresses.additional_info", "user_addresses.latitude", "user_addresses.longitude", "user_addresses.is_default", "areas.name as area")->leftJoin("areas", "areas.id", "=", "user_addresses.area_id")->where("user_addresses.id", $CartMaster->address_id)->first();

            $cart_count = Cart::where("master_id", $CartMaster->id)->count();
            $order_summary = [
                "coupon_code" => $CartMaster->coupon_code,
                "is_giftwrap" => $CartMaster->is_giftwrap,
                "giftwrap_price" => $CartMaster->giftwrap_price,
                "totalqty" => $CartMaster->totalqty,
                "cart_count" => $cart_count,
                "totalprice" => $CartMaster->total,
                "offer_price" => $CartMaster->total,
                "promo_price" => $CartMaster->promo_price,
                "delivery_price" => $CartMaster->delivery,
                "grand_total" => $CartMaster->grandtotal,
            ];

            $carts = Cart::select(
                "carts.master_id as orderId",
                "carts.qty",
                "carts.actual_price as price",
                "carts.product_id as productId",
                "carts.size_id as sizeId",
                "products.name as productName",
                "products.price as productPrice",
                "carts.offer_price as offerPrice",
                "carts.offer_price as price_offer",
                "variants_sub.name as sizeName",
            )
            ->where("carts.master_id", $CartMaster->id)
            ->leftJoin("products", "products.id", "=", "carts.product_id")
            ->leftJoin("productvariants", "productvariants.id", "=", "carts.variant_id")
            ->leftJoin("variants_sub", "variants_sub.id", "=", "productvariants.size_id")
            ->get();

            $carts = $carts->map(function($item) {
                $Productimage = ProductImage::where("product_id", $item->productId)->where("delete_status", "0")->first();
                $item->imageurl = !empty($Productimage) ? $Productimage->imageurl : "";
                return $item;
            });
            
            $tempOrder = [
                "orderId" => $CartMaster->id,
                "order_date" => date("d-m-Y", strtotime($CartMaster->created_at)),
                "order_number" => $CartMaster->order_number,
                "grand_total" => $CartMaster->grandtotal,
                "delivery_price" => $CartMaster->delivery,
                "paymentType" => $CartMaster->paymenttype,
                "addressId" => $CartMaster->address_id,
                "address" => $UserAddresse,
                "order_summary" => $order_summary,
                "orderlists" => $carts,
            ];

            $MyOrdersData[] = $tempOrder;
        }

        $data = $MyOrdersData;
        $message['success'] = "Order list get successfully.";
        return $this->sendResponse($data, $message); 
    }

    public function orderdetails(Request $request){
        
        $validator = Validator::make($request->all(), [
            'orderId' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());
        }

        $CartMaster = CartMaster::where('id', $request->orderId)->first();

        $MyOrdersData = [];
        if(!empty($CartMaster)){
            $UserAddresse = UserAddress::select("user_addresses.id as addressId", "user_addresses.name", "user_addresses.country_mobile", "user_addresses.mobile", "user_addresses.country_landline", "user_addresses.landline", "user_addresses.area_id", "user_addresses.type", "user_addresses.block", "user_addresses.street", "user_addresses.avenue", "user_addresses.building", "user_addresses.floor", "user_addresses.apartment", "user_addresses.additional_info", "user_addresses.latitude", "user_addresses.longitude", "user_addresses.is_default", "areas.name as area")->leftJoin("areas", "areas.id", "=", "user_addresses.area_id")->where("user_addresses.id", $CartMaster->address_id)->first();

            $cart_count = Cart::where("master_id", $CartMaster->id)->count();
            $order_summary = [
                "coupon_code" => $CartMaster->coupon_code,
                "is_giftwrap" => $CartMaster->is_giftwrap,
                "giftwrap_price" => $CartMaster->giftwrap_price,
                "totalqty" => $CartMaster->totalqty,
                "cart_count" => $cart_count,
                "totalprice" => $CartMaster->total,
                "offer_price" => $CartMaster->total,
                "promo_price" => $CartMaster->promo_price,
                "delivery_price" => $CartMaster->delivery,
                "grand_total" => $CartMaster->grandtotal,
            ];

            $carts = Cart::select(
                "carts.master_id as orderId",
                "carts.qty",
                "carts.actual_price as price",
                "carts.product_id as productId",
                "carts.size_id as sizeId",
                "products.name as productName",
                "products.price as productPrice",
                "carts.offer_price as offerPrice",
                "carts.offer_price as price_offer",
                "variants_sub.name as sizeName",
            )
            ->where("carts.master_id", $CartMaster->id)
            ->leftJoin("products", "products.id", "=", "carts.product_id")
            ->leftJoin("productvariants", "productvariants.id", "=", "carts.variant_id")
            ->leftJoin("variants_sub", "variants_sub.id", "=", "productvariants.size_id")
            ->get();

            $carts = $carts->map(function($item) {
                $Productimage = ProductImage::where("product_id", $item->productId)->where("delete_status", "0")->first();
                $item->imageurl = !empty($Productimage) ? $Productimage->imageurl : "";
                return $item;
            });
            
            $tempOrder = [
                "orderId" => $CartMaster->id,
                "order_date" => date("d-m-Y", strtotime($CartMaster->created_at)),
                "order_number" => $CartMaster->order_number,
                "grand_total" => $CartMaster->grandtotal,
                "delivery_price" => $CartMaster->delivery,
                "paymentType" => $CartMaster->paymenttype,
                "addressId" => $CartMaster->address_id,
                "address" => $UserAddresse,
                "order_summary" => $order_summary,
                "orderlists" => $carts,
            ];

            $MyOrdersData = $tempOrder;
        }

        $data['MyOrdersData'] = $MyOrdersData;
        $message['success'] = "Order detail get successfully.";
        return $this->sendResponse($data, $message); 
    }

    public function sizecharts(){
        $SizeChart = SizeChart::where('delete_status','0')->where('type',"size_chart")->first();

        $data["description"] = !empty($SizeChart) ? $SizeChart->description : "";
        $message['success'] = "Size charts get successfully.";
        return $this->sendResponse($data, $message); 
    }

    public function legal_terms(){
        $SizeChart = SizeChart::where('delete_status','0')->where('type',"legal")->first();

        $data["description"] = !empty($SizeChart) ? $SizeChart->description : "";
        $message['success'] = "Legal terms get successfully.";
        return $this->sendResponse($data, $message); 
    }

    public function terms_conditions(){
        $terms_conditions = Faq::where('delete_status','0')->where('type',"terms")->first();

        $data["description"] = !empty($terms_conditions) ? $terms_conditions->content : "";
        $message['success'] = "Terms and condition get successfully.";
        return $this->sendResponse($data, $message); 
    }

}

