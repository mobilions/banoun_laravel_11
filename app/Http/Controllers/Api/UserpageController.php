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

        if(!empty($request->action)){ $action = $request->action; }

        else{ $action = 'lists';  }

        $msg = 'Kid Details';



        if ($action == 'create') {

            

            $validator = Validator::make($request->all(), [

                'name' => 'required',

                'gender' => 'required',

            ]);



            if($validator->fails()){

                return $this->sendError('Name/Type is Required.', $validator->errors());       

            }

            $imgfile    = '';

            $path   = $request->file('imgfile');

            if (!empty($path)) {

                $storedPath  = $path->store('image', 'public');
                $imgfile = 'storage/'.$storedPath;

            }



            $kidId = DB::table('user_kids')->insertGetId(['user_id'=>auth("api")->user()->id,'name'=>$request->name,'gender'=>$request->gender,'dob'=>$request->dob,'imgfile'=>$imgfile]);

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

                $storedPath  = $path->store('image', 'public');
                $imgfile = 'storage/'.$storedPath;

            }

            else{

                $kidimg = DB::table('user_kids')->select('imgfile')->where('id',$request->kidId)->first();
                
                $imgfile = $kidimg->imgfile;

            }



            $kidId = DB::table('user_kids')->where('id',$request->kidId)->update(['user_id'=>auth("api")->user()->id,'name'=>$request->name,'gender'=>$request->gender,'dob'=>$request->dob,'imgfile'=>$imgfile]);

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

            $kids = DB::table('user_kids')->select('user_id as userId','id as kidId','name','gender','dob','imgfile')->where('user_id',auth("api")->user()->id)->where('delete_status','0')->get();

            $msg = 'Kid Details Deleted';
            $massage['success'] = $msg;
            return $this->sendResponse($kids, $massage);
        }



        $kids = DB::table('user_kids')->select('user_id as userId','id as kidId','name','gender','dob','imgfile')->where('user_id',auth("api")->user()->id)->where('delete_status','0')->get();


        $massage['success'] = $msg;
        if (count($kids)>0) { return $this->sendResponse($kids, $massage); } 

        else { return $this->sendError(['error'=>'No Kids Available']); }



    }

}

