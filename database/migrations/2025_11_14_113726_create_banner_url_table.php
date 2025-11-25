<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerUrlTable extends Migration
{
    public function up()
    {
        Schema::create('banner_url', function (Blueprint $table) {
            $table->integer('id');
            $table->string('name', 255)->nullable();
            $table->string('base_id', 255)->nullable();
            $table->string('base_url', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('banner_url');
    }
}
