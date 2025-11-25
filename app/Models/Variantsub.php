<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Model;
use App\Traits\SoftDeleteByStatus;



class Variantsub extends Model

{
    use SoftDeleteByStatus;

	protected  $table="variants_sub";

    public static function FindName($id){

        $log = Variantsub::select('name','name_ar')->where('id',$id)->first();

        $name =  '';

        if($log){ $name =  $log->name.' '.$log->name_ar; }

		return "$name";

	}

}

