<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserKidsTable extends Migration
{
    public function up()
    {
        Schema::create('user_kids', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('user_id')->nullable();
            $table->string('name', 200)->nullable();
            $table->string('type', 20)->nullable();
            $table->date('dob')->nullable();
            $table->string('imgfile', 255)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('delete_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_kids');
    }
}
