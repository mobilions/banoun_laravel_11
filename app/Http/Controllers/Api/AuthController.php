<?php



namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Controllers\Api\BaseController as BaseController;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

use Validator;

use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Http\Requests\Auth\SendPhoneOtpRequest;
use App\Http\Requests\Auth\UpdatePhoneRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;

use Illuminate\Support\Facades\Storage;



class AuthController extends BaseController

{

    public function register(RegisterRequest $request)

    {
        $request->validated();
        if ($request->filled('email')) {
            $emailTaken = User::where('email', $request->email)
                ->where('is_verified', 1)
                ->exists();
        
            if ($emailTaken) {
                return $this->sendError(['email' => 'The email has already been taken.']);
            }
        }
        
        $phoneTaken = User::where('phone', $request->phone)->where('is_verified', 1)->exists();
        if ($phoneTaken) {
            return $this->sendError(['phone' => 'The phone number has already been taken.']);
        }
        
        $usernotverified = User::where('phone', $request->phone)->first();
        
        if (!empty($usernotverified) && !$usernotverified->is_verified) {
            $this->setOtpForUser($usernotverified);
            $user = $this->getuser_data($usernotverified->id);
            $message['success'] = 'OTP sent to your registered contact.';
            
            return $this->sendResponse($user, $message);
        }
        $input = $request->only(['name', 'phone', 'country_code']);
        if ($request->filled('email')) {
            $input = $request->only(['name', 'email', 'phone', 'country_code']);
        }
        $input['password'] = bcrypt(Str::random(32));

        $user = new User($input);
        $user->is_verified = 0;
        $user->save();
        $this->setOtpForUser($user);

        $user = $this->getuser_data($user->id);
        $message['success'] = 'OTP sent to your registered contact.';
        
        return $this->sendResponse($user, $message);
        
    }



    public function verifyotp(VerifyOtpRequest $request)

    {
        $user = User::where('id',$request->userId)->where('is_verified',0)->first();

        if(!empty($user) && $this->otpMatches($user, $request->otp)){ 

            $user->is_verified = 1;
            $user->otp = null;
            $user->save();

            $success['otp'] =  $user->otp;
            $success['userId'] =  $user->id;

            $success['name'] =  $user->name;

            $success['phone'] =  $user->phone;
            $success['imgfile'] =  $user->imgfile;

            $success['country_code'] =  $user->country_code;

            $success['email'] =  $user->email;
            $success['credit_balance'] =  $user->credit_balance;

            $success['token'] =  $user->createToken('MyApp')->accessToken; 

            $message['success'] = 'OTP Verified successfully.';
            return $this->sendResponse($success, $message);

        } 

        else{ 

            return $this->sendError(['otp'=>'Incorrect Otp.']);

        } 

    }



    public function updatepassword(Request $request)

    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }

        $user = User::where('id', auth("api")->user()->id)->first();

        if(!empty($user)){ 

            $user->password = bcrypt($request->password);
            $user->is_verified = 1;
            $user->otp = null;
            $user->save();

            $success['otp'] =  $user->otp;
            $success['userId'] =  $user->id;

            $success['name'] =  $user->name;
            $success['imgfile'] =  $user->imgfile;

            $success['phone'] =  $user->phone;

            $success['country_code'] =  $user->country_code;

            $success['email'] =  $user->email;
            $success['credit_balance'] =  $user->credit_balance;
            
            $success['token'] =  request()->bearerToken();

            $message['success'] = 'Password Updated successfully.';
            return $this->sendResponse($success, $message);

        } 

        else{ 

            return $this->sendError(['error'=>'Incorrect Otp.']);

        } 

    }





    public function updatename(Request $request)

    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }


        User::where('id', auth("api")->user()->id)->update(['name'=>$request->name]);

        $this->updateuserchanges(auth("api")->user()->id,'name',$request->name);

        $success = $this->getuser_data(auth("api")->user()->id);

        if (!empty($success)) {
            $message['success'] = 'User name updated successfully.';
            return $this->sendResponse($success, $message);
        }  else {
            return $this->sendError(['error'=>'Unauthorized']);
        }

    }



    public function sendphoneotp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_phone' => 'required|string|max:20',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }
        $userdetail = User::where('id', auth("api")->user()->id)->first();

        if (!empty($userdetail)) {

            $userdetail->is_verified = 0;
            $userdetail->save();
            $this->setOtpForUser($userdetail);

            $success['otp'] =  $userdetail->otp;
            $success['userId'] =  $userdetail->id;

            $success['name'] =  $userdetail->name;

            $success['phone'] =  $userdetail->phone;

            $success['country_code'] =  $userdetail->country_code;
            $success['imgfile'] =  $userdetail->imgfile;

            $success['email'] =  $userdetail->email;
            $success['credit_balance'] =  $userdetail->credit_balance;

            $success['token'] =  request()->bearerToken(); 



            $this->updateuserchanges(auth("api")->user()->id,'phone',$request->new_phone,$status="0");

            $message['success'] = 'OTP sent to your registered contact.';

            return $this->sendResponse($success, $message);

        }

        else{ 

            return $this->sendError(['error'=>'Phone number not found.']);

        } 

    }

    public function updatephone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|digits_between:5,6',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }
        $user = User::where('id',auth("api")->user()->id)->first();

        if(!empty($user) && $this->otpMatches($user, $request->otp)){ 

            $success['otp'] =  $user->otp;
            $success['userId'] =  $user->id;

            $success['name'] =  $user->name;
            $success['imgfile'] =  $user->imgfile;

            $success['phone'] =  $user->phone;

            $success['country_code'] =  $user->country_code;

            $success['email'] =  $user->email;
            $success['credit_balance'] =  $user->credit_balance;

            $success['token'] =  request()->bearerToken();



            $lastphone = DB::table('user_changes')->where('user_id',$user->id)->where('field','phone')->where('is_verified',0)->orderBy('id','desc')->first();

            if (!empty($lastphone)) {

                $user->is_verified = 1;
                $user->phone = $lastphone->value;
                $user->otp = null;
                $user->save();

                DB::table('user_changes')->where('id',$lastphone->id)->update(['is_verified'=>1]);

                $success['phone'] =  $lastphone->value;

            } else {
                $user->is_verified = 1;
                $user->otp = null;
                $user->save();
            }
            $message['success'] = 'Phone number updated successfully.';
            return $this->sendResponse($success, $message);

        } 

        else{ 

            return $this->sendError(['error'=>'Incorrect Otp.']);

        }

    }

    public function sendemailotp(Request $request)
    {
        $userdetail = User::where('id', auth("api")->user()->id)->first();

        if (!empty($userdetail)) {

            $userdetail->is_verified = 0;
            $userdetail->save();
            $this->setOtpForUser($userdetail);

            $success['otp'] =  $userdetail->otp;
            $success['userId'] =  $userdetail->id;

            $success['name'] =  $userdetail->name;

            $success['phone'] =  $userdetail->phone;

            $success['country_code'] =  $userdetail->country_code;
            $success['imgfile'] =  $userdetail->imgfile;

            $success['email'] =  $userdetail->email;
            $success['credit_balance'] =  $userdetail->credit_balance;

            $success['token'] =  request()->bearerToken(); 



            $this->updateuserchanges($userdetail->id,'email',$request->new_email,$status="0");

            $message['success'] = 'OTP sent to your registered contact.';

            return $this->sendResponse($success, $message);

        }

        else{ 

            return $this->sendError(['error'=>'Email not found.']);

        } 

    }

    public function updateemail(Request $request)
    {

        $user = User::where('id',auth("api")->user()->id)->first();

        if(!empty($user) && $this->otpMatches($user, $request->otp)){ 

            $success['otp'] =  $user->otp;
            $success['userId'] =  $user->id;

            $success['name'] =  $user->name;
            $success['imgfile'] =  $user->imgfile;

            $success['phone'] =  $user->phone;

            $success['country_code'] =  $user->country_code;

            $success['email'] =  $user->email;
            $success['credit_balance'] =  $user->credit_balance;

            $success['token'] =  request()->bearerToken(); 



            $lastemail = DB::table('user_changes')->where('user_id',$user->id)->where('field','email')->where('is_verified',0)->orderBy('id','desc')->first();

            if (!empty($lastemail)) {

                $user->is_verified = 1;
                $user->email = $lastemail->value;
                $user->otp = null;
                $user->save();

                DB::table('user_changes')->where('id',$lastemail->id)->update(['is_verified'=>1]);

                $success['email'] =  $lastemail->value;

            } else {
                $user->is_verified = 1;
                $user->otp = null;
                $user->save();
            }
            $message['success'] = 'Email address updated successfully.';
            return $this->sendResponse($success, $message);

        } 

        else{ 

            return $this->sendError(['error'=>'Incorrect Otp.']);

        }

    }



    public function forgotpassword(ForgotPasswordRequest $request)

    {
        $request->validated();

        $userdetail = User::where('phone',$request->phone)->where('country_code',$request->country_code)->first();

        if (!empty($userdetail)) {

            $this->setOtpForUser($userdetail);
            $userdetail->is_verified = 0;
            $userdetail->save();

            
            $success['otp'] =  $userdetail->otp;
            $success['userId'] =  $userdetail->id;
            $success['name'] =  $userdetail->name;
            $success['imgfile'] =  $userdetail->imgfile;
            $success['phone'] =  $userdetail->phone;
            $success['country_code'] =  $userdetail->country_code;
            $success['email'] =  $userdetail->email;
            $success['credit_balance'] =  $userdetail->credit_balance;
            $success['token'] =  request()->bearerToken(); 

            $message['success'] = 'OTP sent to your registered contact.';

            return $this->sendResponse($success, $message);

        }

        else{ 

            return $this->sendError(['error'=>'Unauthorized']);

        } 

    }



    public function changepassword(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'current_password' => 'required',
            'new_password' => 'required',

        ]);

   

        if($validator->fails()){

            return $this->sendError($validator->errors());       

        }



        if ($request->current_password == $request->new_password) {

            return $this->sendError('Old password and new password cannot be same.', $validator->errors());   

        }



        $userdetail = User::where('id',auth("api")->user()->id)->first();

        if (!empty($userdetail) && password_verify($request->current_password,$userdetail->password)) {

            $success['otp'] =  $userdetail->otp;
            $success['userId'] =  $userdetail->id;

            $success['name'] =  $userdetail->name;
            $success['imgfile'] =  $userdetail->imgfile;

            $success['phone'] =  $userdetail->phone;

            $success['country_code'] =  $userdetail->country_code;

            $success['email'] =  $userdetail->email;
            $success['credit_balance'] =  $userdetail->credit_balance;

            $success['token'] =  request()->bearerToken(); 

            User::where('id',$userdetail->id)->update(['password'=>bcrypt($request->new_password)]);

            $this->updateuserchanges($userdetail->id,'password',bcrypt($request->new_password));
            $message['success'] = 'Password Updated successfully.';

            return $this->sendResponse($success, $message);

        }

        else{ 

            return $this->sendError(['error'=>'Current Password is Incorrect.']);

        } 

    }



    public function login(LoginRequest $request)

    {
        $request->validated();

        $useremail = User::select('email','phone','is_verified')->where(function($query) use ($request) {
            $query->where('email',$request->username)
                  ->orWhere('phone',$request->username);
        })->where("delete_status", "0")->first();

        if (!empty($useremail)) {

        if ($useremail->is_verified == 1) {

            if(Auth::attempt(['phone' => $useremail->phone, 'password' => $request->password])){ 

                $user = Auth::user(); 

                $success['otp'] =  $user->otp;
                $success['userId'] =  $user->id;
                $success['name'] =  $user->name;
                $success['imgfile'] =  $user->imgfile;
                $success['phone'] =  $user->phone;
                $success['country_code'] =  $user->country_code;
                $success['email'] =  $user->email;
                $success['credit_balance'] =  $user->credit_balance;
                $success['token'] =  $user->createToken('MyApp')->accessToken; 

                $message['success'] = 'User login successfully.';

                return $this->sendResponse($success, $message);
            } else { 
                $message['password'] = 'Password Incorrect.';
                return $this->sendError($message);
            } 
        } else { 
            return $this->sendError(['error'=>'Unauthorized']);
        } 

        } else { 
            $message['username'] = 'Username Incorrect.';
            return $this->sendError($message);
        } 
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user && $user->token()) {
            $user->token()->revoke();
        }

        $message['success'] = 'Logout successfully';
        return $this->sendResponse([], $message);
    }

    public function deleteuser(Request $request)
    {
        $user = $request->user();

        User::where("id", $user->id)->update([
            "delete_status" => "1",
            "email" => null,
            "phone" => null,
        ]);
        
        $message['success'] = 'User deleted successfully';
        return $this->sendResponse([], $message);
    }

    public function updateimage(Request $request){

        $validator = Validator::make($request->all(), [
            'imgfile' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }

        $user = User::where('id', auth("api")->user()->id)->first();
        
        $path   = $request->file('imgfile');
       
        if (!empty($path)) {
            $store  = Storage::putFile('public/image', $path);
            $imgfile = config('app.imgurl').basename($store);
            $user->imgfile = $imgfile;
            $user->save();
        }

        $success['otp'] =  $user->otp;
        $success['userId'] =  $user->id;
        $success['name'] =  $user->name;
        $success['imgfile'] =  $user->imgfile;
        $success['phone'] =  $user->phone;
        $success['country_code'] =  $user->country_code;
        $success['email'] =  $user->email;
        $success['credit_balance'] =  $user->credit_balance;
        $success['token'] =  request()->bearerToken();

        $message['success'] = 'Profile image updated successfully';
        return $this->sendResponse($user, $message);
    }




    public function carousals($limit="5")

    {

        if (!empty($_GET['limit'])) { $limit=$_GET['limit']; }

        if (!empty($_GET['lang']) && $_GET['lang'] == 'ar') {            

            $category = \App\Carousal::select('name_ar as name','description_ar as description','imageurl');

        }

        else{

            $category = \App\Carousal::select('name','description','imageurl');

        }

        $category = $category->where('delete_status', '0')->limit($limit)->get();



        if (!empty($category)) {

            $message['success'] = 'Carousal Lists';

            return $this->sendResponse($category, $message);

        } 

        else {

            return $this->sendError('No Carousals Available', ['error'=>'Unauthorized']);

        }

    }



    protected function generateOtp(): string
    {
        return (string) random_int(100000, 999999);
    }

    protected function setOtpForUser(User $user): void
    {
        // $otp = $this->generateOtp();
        $otp = 12345;
        $user->otp = $otp;
        $user->save();
        // $this->dispatchOtp($user, $otp);
    }

    protected function otpMatches(User $user, $otp): bool
    {
        if (empty($user->otp)) {
            return false;
        }

        if (Hash::check($otp, $user->otp)) {
            return true;
        }

        return hash_equals((string) $user->otp, (string) $otp);
    }

    protected function dispatchOtp(User $user, string $otp): void
    {
        if (!empty($user->email)) {
            try {
                Mail::raw("Your verification code is {$otp}", function ($message) use ($user) {
                    $message->to($user->email)->subject('Your OTP Code');
                });
            } catch (\Throwable $th) {
                Log::warning('Unable to send OTP email.', [
                    'user_id' => $user->id,
                    'error' => $th->getMessage(),
                ]);
            }
        } else {
            Log::info('OTP generated for user without email.', ['user_id' => $user->id]);
        }
    }

    public function getuser(){
        $id = auth("api")->user()->id;
        $user = User::select("otp", "id as userId", "name", "imgfile", "phone", "country_code", "email", "credit_balance")->where("id", $id)->first();

        $user['token'] = request()->bearerToken();
        $message["success"] = 'User profile get successfully.';
        return $this->sendResponse($user, $message);
    }

    public function getuser_data($id) {
        $user = User::Find($id);

        if(!empty($user)){ 

            $success['otp'] =  $user->otp;
            $success['userId'] =  $user->id;

            $success['name'] =  $user->name;
            $success['imgfile'] =  $user->imgfile;

            $success['phone'] =  $user->phone;

            $success['country_code'] =  $user->country_code;

            $success['email'] =  $user->email;
            $success['credit_balance'] =  $user->credit_balance;

            $success['token'] =  $user->createToken('MyApp')->accessToken; 

        } 

        else{

            $success = '';

        }

        return $success;

    }



    public function updateuserchanges($user,$field,$value,$status="1") {

        DB::table('user_changes')->insert(['user_id'=>$user, 'field'=>$field, 'value'=>$value, 'is_verified'=>$status]);

    }



    public function errorapi()

    {

        $response = [

            'success' => false,

            'message' => 'Unauthorized',

        ];

        return response()->json($response, 200);

    }

}