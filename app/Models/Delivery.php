<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Model;
use App\Traits\SoftDeleteByStatus;



class Delivery extends Model

{
    use SoftDeleteByStatus;

	protected  $table="deliveries";



    public static function FindName($id){

        $log = Delivery::select('name','name_ar')->where('id',$id)->first();

        $name =  '';

        if($log){ $name =  $log->name.' '.$log->name_ar; }

		return "$name";

	}

}

