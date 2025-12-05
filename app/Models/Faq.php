<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Model;



class Faq extends Model

{

	protected  $table="qa_details";

	

    protected $fillable = [

        'title','content', 'title_ar', 'content_ar','type', 'created_by', 'updated_by', 'delete_status',

    ];

}

