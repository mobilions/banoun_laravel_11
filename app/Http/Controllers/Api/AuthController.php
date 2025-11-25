<?php



namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Controllers\Api\BaseController as BaseController;

use App\Models\User;
use App\Models\Carousal;

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



class AuthController extends BaseController

{

    public function register(RegisterRequest $request)

    {
        $request->validated();

        $emailTaken = User::where('email', $request->email)->where('is_verified', 1)->exists();
        if ($emailTaken) {
            return $this->sendError1(['email' => 'The email has already been taken.']);
        }

        $phoneTaken = User::where('phone', $request->phone)->where('is_verified', 1)->exists();
        if ($phoneTaken) {
            return $this->sendError1(['phone' => 'The phone number has already been taken.']);
        }

        $usernotverified = User::where('email', $request->email)
            ->orWhere('phone', $request->phone)
            ->first();

        if (!empty($usernotverified) && !$usernotverified->is_verified) {
            $this->setOtpForUser($usernotverified);
            return $this->sendResponse([
                'userId' => $usernotverified->id,
                'message' => 'OTP sent to your registered contact.'
            ], 'OTP dispatched.');
        }

        $input = $request->only(['name', 'email', 'phone', 'country_code']);
        $input['password'] = bcrypt(Str::random(32));
        $user = new User($input);
        $user->is_verified = 0;
        $user->save();

        $this->setOtpForUser($user);

        return $this->sendResponse([
            'userId' => $user->id,
            'message' => 'OTP sent to your registered contact.'
        ], 'Registration initiated.');

    }



    public function verifyotp(VerifyOtpRequest $request)

    {
        $user = User::where('id',$request->userId)->where('is_verified',0)->first();

        if(!empty($user) && $this->otpMatches($user, $request->otp)){ 

            $user->is_verified = 1;
            $user->otp = null;
            $user->save();

            $success['userId'] =  $user->id;

            $success['name'] =  $user->name;

            $success['phone'] =  $user->phone;

            $success['country_code'] =  $user->country_code;

            $success['email'] =  $user->email;

            $success['token'] =  $user->createToken('MyApp')->accessToken; 

            return $this->sendResponse($success, 'OTP Verified successfully.');

        } 

        else{ 

            return $this->sendError('Incorrect Otp.', ['error'=>'Unauthorized']);

        } 

    }



    public function updatepassword(UpdatePasswordRequest $request)

    {

        $user = User::where('id',$request->userId)->first();

        if(!empty($user) && $this->otpMatches($user, $request->otp)){ 

            $user->password = bcrypt($request->password);
            $user->is_verified = 1;
            $user->otp = null;
            $user->save();

            $success['userId'] =  $user->id;

            $success['name'] =  $user->name;

            $success['phone'] =  $user->phone;

            $success['country_code'] =  $user->country_code;

            $success['email'] =  $user->email;

            $success['token'] =  $user->createToken('MyApp')->accessToken; 

            return $this->sendResponse($success, 'Password Updated successfully.');

        } 

        else{ 

            return $this->sendError('Incorrect Otp.', ['error'=>'Unauthorized']);

        } 

    }





    public function updatename(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'userId' => 'required',

        ]);

   

        if($validator->fails()){

            return $this->sendError('userId is required.', $validator->errors());       

        }



        User::where('id',$request->userId)->update(['name'=>$request->name]);

        $this->updateuserchanges($request->userId,'name',$request->name);

        $success = $this->getuser($request->userId);

        if (!empty($success)) {

            return $this->sendResponse($success, 'User register successfully.');

        }

        else{

            return $this->sendError('User not found.', ['error'=>'Unauthorized']);

        }

    }



    public function sendphoneotp(SendPhoneOtpRequest $request)

    {

        $userdetail = User::where('id',$request->userId)->first();

        if (!empty($userdetail)) {

            $userdetail->is_verified = 0;
            $userdetail->save();
            $this->setOtpForUser($userdetail);

            $success['userId'] =  $userdetail->id;

            $success['name'] =  $userdetail->name;

            $success['phone'] =  $userdetail->phone;

            $success['country_code'] =  $userdetail->country_code;

            $success['email'] =  $userdetail->email;

            $success['token'] =  $userdetail->createToken('MyApp')->accessToken; 



            $this->updateuserchanges($request->userId,'phone',$request->new_phone,$status="0");

            $success['message'] = 'OTP sent to your registered contact.';

            return $this->sendResponse($success, 'Otp sent successfully.');

        }

        else{ 

            return $this->sendError('Phone number not found.', ['error'=>'Unauthorized']);

        } 

    }



    public function updatephone(UpdatePhoneRequest $request)

    {

        $user = User::where('id',$request->userId)->first();

        if(!empty($user) && $this->otpMatches($user, $request->otp)){ 

            $success['userId'] =  $user->id;

            $success['name'] =  $user->name;

            $success['phone'] =  $user->phone;

            $success['country_code'] =  $user->country_code;

            $success['email'] =  $user->email;

            $success['token'] =  $user->createToken('MyApp')->accessToken; 



            $lastphone = DB::table('user_changes')->where('user_id',$request->userId)->where('field','phone')->where('is_verified',0)->orderBy('id','desc')->first();

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

            return $this->sendResponse($success, 'Phone number updated successfully.');

        } 

        else{ 

            return $this->sendError('Incorrect Otp.', ['error'=>'Unauthorized']);

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

            return $this->sendResponse([
                'userId' => $userdetail->id,
                'message' => 'OTP sent to your registered contact.'
            ], 'OTP dispatched.');

        }

        else{ 

            return $this->sendError('Phone number not found.', ['error'=>'Unauthorized']);

        } 

    }



    public function changepassword(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'userId' => 'required',

        ]);

   

        if($validator->fails()){

            return $this->sendError('userId is required.', $validator->errors());       

        }



        if ($request->current_password == $request->new_password) {

            return $this->sendError('Old password and new password cannot be same.', $validator->errors());   

        }



        $userdetail = User::where('id',$request->userId)->first();

        if (!empty($userdetail) && password_verify($request->current_password,$userdetail->password)) {

            $success['userId'] =  $userdetail->id;

            $success['name'] =  $userdetail->name;

            $success['phone'] =  $userdetail->phone;

            $success['country_code'] =  $userdetail->country_code;

            $success['email'] =  $userdetail->email;

            $success['token'] =  $userdetail->createToken('MyApp')->accessToken; 

            User::where('id',$userdetail->id)->update(['password'=>bcrypt($request->new_password)]);

            $this->updateuserchanges($userdetail->id,'password',bcrypt($request->new_password));

            return $this->sendResponse($success, 'Password Updated successfully.');

        }

        else{ 

            return $this->sendError('Current Password is Incorrect.', ['error'=>'Unauthorized']);

        } 

    }



    public function login(LoginRequest $request)

    {
        $request->validated();

        $useremail = User::select('email','is_verified')->where(function($query) use ($request) {
            $query->where('email',$request->username)
                  ->orWhere('phone',$request->username);
        })->first();

        if (!empty($useremail)) {

        if ($useremail->is_verified == 1) {

            if(Auth::attempt(['email' => $useremail->email, 'password' => $request->password])){ 

                $user = Auth::user(); 

                $success['userId'] =  $user->id;

                $success['name'] =  $user->name;

                $success['phone'] =  $user->phone;

                $success['country_code'] =  $user->country_code;

                $success['email'] =  $user->email;

                $success['token'] =  $user->createToken('MyApp')->accessToken; 

                return $this->sendResponse($success, 'User login successfully.');

            }

            else{ 

                $message['password'] = 'Password Incorrect.';

                return $this->sendError1($message);

            } 

        }

        else{ 

            return $this->sendError('Otp Not Verified.', ['error'=>'Unauthorized']);

        } 

        } 

        else{ 

            $message['username'] = 'Username Incorrect.';

            return $this->sendError1($message);

        } 

    }



    public function logout(Request $request)

    {
        $user = $request->user();

        if ($user && $user->token()) {
            $user->token()->revoke();
        }

        return $this->sendResponse([], 'Logout successfully');

    }





    public function carousals($limit="5")

    {

        if (!empty($_GET['limit'])) { $limit=$_GET['limit']; }

        if (!empty($_GET['lang']) && $_GET['lang'] == 'ar') {            

            $category = Carousal::select('name_ar as name','description_ar as description','imageurl');

        }

        else{

            $category = Carousal::select('name','description','imageurl');

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
        $otp = $this->generateOtp();
        $user->otp = Hash::make($otp);
        $user->save();
        $this->dispatchOtp($user, $otp);
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



    public function getuser($id) {

        $user = User::Find($id);

        if(!empty($user)){ 

            $success['userId'] =  $user->id;

            $success['name'] =  $user->name;

            $success['phone'] =  $user->phone;

            $success['country_code'] =  $user->country_code;

            $success['email'] =  $user->email;

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