<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductMoreInfoTable extends Migration
{
    public function up()
    {
        Schema::create('product_more_info', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('product_id')->nullable();
            $table->string('country_origin', 200)->nullable();
            $table->string('manufacturer', 255)->nullable();
            $table->string('importer', 255)->nullable();
            $table->string('packer', 255)->nullable();
            $table->integer('delete_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_more_info');
    }
}
