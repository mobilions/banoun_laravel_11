<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Model;



class Userkids extends Model

{

	protected  $table="user_kids";

	

    protected $fillable = [

        'name','type', 'dob', 'imgfile','user_id',

    ];

    public function getImgfileAttribute($value){
        return $value != "" ? asset($value) : "";
    }
}

