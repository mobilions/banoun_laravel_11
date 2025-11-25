<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderstatusTable extends Migration
{
    public function up()
    {
        Schema::create('orderstatus', function (Blueprint $table) {
            $table->integer('id');
            $table->string('name', 100)->nullable();
            $table->string('color', 10)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orderstatus');
    }
}
