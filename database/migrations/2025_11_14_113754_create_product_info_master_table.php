<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductInfoMasterTable extends Migration
{
    public function up()
    {
        Schema::create('product_info_master', function (Blueprint $table) {
            $table->integer('id');
            $table->string('name', 200)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_info_master');
    }
}
