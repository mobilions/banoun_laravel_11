<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductInfoTable extends Migration
{
    public function up()
    {
        Schema::create('product_info', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('product_id')->nullable();
            $table->integer('master_id')->nullable();
            $table->text('detail')->nullable();
            $table->integer('delete_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_info');
    }
}
