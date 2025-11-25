<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Validator;

class UserpageController extends BaseController
{
    public function kids(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'userId' => 'required|numeric',
        ]);

        if($validator->fails()){
            return $this->sendError('UserId is Required.', $validator->errors());       
        }
        if(!empty($request->action)){ $action = $request->action; }
        else{ $action = 'lists';  }
        $msg = 'Kid Details';

        if ($action == 'create') {
            
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'type' => 'required',
            ]);

            if($validator->fails()){
                return $this->sendError('Name/Type is Required.', $validator->errors());       
            }
            $imgfile    = '';
            $path   = $request->file('imgfile');
            if (!empty($path)) {
                $store  = Storage::putFile('public/image', $path);
                $imgfile = config('app.imgurl').basename($store);
            }

            $kidId = DB::table('user_kids')->insertGetId(['user_id'=>$request->userId,'name'=>$request->name,'type'=>$request->type,'dob'=>$request->dob,'imgfile'=>$imgfile]);
            $msg = 'Kid Details Created';
        }
        
        else if ($action == 'update') {
            
            $validator = Validator::make($request->all(), [
                'kidId' => 'required',
            ]);

            if($validator->fails()){
                return $this->sendError('kidId is Required.', $validator->errors());       
            }
            
            $imgfile    = '';
            $path   = $request->file('imgfile');
            if (!empty($path)) {
                $store  = Storage::putFile('public/image', $path);
                $imgfile = config('app.imgurl').basename($store);
            }
            else{
                $kidimg = DB::table('user_kids')->select('imgfile')->where('id',$request->kidId)->first();
                $imgfile = $kidimg->imgfile;
            }

            $kidId = DB::table('user_kids')->where('id',$request->kidId)->update(['user_id'=>$request->userId,'name'=>$request->name,'type'=>$request->type,'dob'=>$request->dob,'imgfile'=>$imgfile]);
            $msg = 'Kid Details Updated';
        }
        
        else if ($request->action == 'delete') {
            
            $validator = Validator::make($request->all(), [
                'kidId' => 'required',
            ]);

            if($validator->fails()){
                return $this->sendError('kidId is Required.', $validator->errors());       
            }

            $kidId = DB::table('user_kids')->where('id',$request->kidId)->update(['delete_status'=>'1']);
            $msg = 'Kid Details Deleted';
        }

        $kids = DB::table('user_kids')->select('user_id as userId','id as kidId','name','type','dob','imgfile')->where('user_id',$request->userId)->where('delete_status','0')->get();

        if (count($kids)>0) { return $this->sendResponse($kids, $msg); } 
        else { return $this->sendError('No Kids Available', ['error'=>'Unauthorized']); }

    }
}
