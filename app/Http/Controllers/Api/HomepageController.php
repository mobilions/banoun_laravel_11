<?php



namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Controllers\Api\BaseController as BaseController;

use DB;

use App\Models\Category;

use App\Models\Product;

use App\Models\Subcategory;

use App\Models\Banner;

use App\Models\Topcollection;

use App\Models\usbanner;

use App\Models\Setting;
use App\Models\Brand;
use App\Models\UserAddress;
use App\Models\ProductImage;



class HomepageController extends BaseController

{



    public function index()

    {

        $category = \App\Models\Category::all();

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

            return $this->sendResponse($category, 'Category Lists');

        } 

        else {

            return $this->sendError('No Category Available', ['error'=>'Unauthorized']);

        }

    }



    public function productlist($limit="5")

    {

        if (!empty($_GET['limit'])) { $limit=$_GET['limit']; }

        if (!empty($_GET['lang']) && $_GET['lang'] == 'ar') {            

            $product = Product::select('id as productId','name_ar as name','description_ar as description','imageurl','category_id as categoryId','subcategory_id as subcategoryId','brand_id as brandId');

        }

        else{

            $product = Product::select('id as productId','name','description','imageurl','category_id as categoryId','subcategory_id as subcategoryId','brand_id as brandId');

        }

        $product = $product->where('delete_status', '0')->limit($limit)->get();



        if (!empty($product)) {

            return $this->sendResponse($product, 'Product Lists');

        } 

        else {

            return $this->sendError('No Product Available', ['error'=>'Unauthorized']);

        }

    }

    public function homepage(){
        $topbanners = Topcollection::select('imageurl', 'type', 'categoryId')->where('delete_status','0')->take(4)->get();
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

        if (!empty($_GET['lang']) && $_GET['lang'] == 'ar') {            

            $subcategory = Subcategory::select('id as subcategoryId','name_ar as name','description_ar as description','imageurl','category_id as categoryId');

        }

        else{

            $subcategory = Subcategory::select('id as subcategoryId','name','description','imageurl','category_id as categoryId');

        }

        $subcategory = $subcategory->where('delete_status', '0')->get();



        if (!empty($subcategory)) {

            return $this->sendResponse($subcategory, 'SubCategory Lists');

        } 

        else {

            return $this->sendError('No SubCategory Available', ['error'=>'Unauthorized']);

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
            return $this->sendError('No Brand Available', ['error'=>'Unauthorized']);
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

        if (usbanner::where('delete_status', '0')->exists()) {

            $category = usbanner::select('name','name_ar','description','description_ar','imageurl','shopby','category_id as categoryId')->where('delete_status', '0')->get()->toJson(JSON_PRETTY_PRINT);

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

        }

        else{

            $details = DB::table('qa_details')->select('title','content')->where('type', 'faq')->where('delete_status', '0')->get();

        }



        if (count($details)>0) { 

            return $this->sendResponse($details, "FAQ Details"); 

        } 

        else { 

            return $this->sendError('No FAQ Details', ['error'=>'Unauthorized']); 

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

            return $this->sendResponse($details, "About Details"); 

        } 

        else { 

            return $this->sendError('No About Details', ['error'=>'Unauthorized']); 

        }

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

        $UserAddresses = UserAddress::where("user_id", auth("api")->user()->id)->where("delete_status", "0")->get();
        
        $message['success'] = "Addresses list.";
        return $this->sendResponse($UserAddresses, $message); 
    }

}

