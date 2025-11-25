<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCreditsTable extends Migration
{
    public function up()
    {
        Schema::create('user_credits', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('user_id')->nullable();
            $table->string('balance');
            $table->integer('delete_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_credits');
    }
}
