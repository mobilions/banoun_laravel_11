<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductvariantsTable extends Migration
{
    public function up()
    {
        Schema::create('productvariants', function (Blueprint $table) {
            $table->id();
;
            $table->integer('product_id');
            $table->integer('size_id')->nullable();
            $table->integer('color_id')->nullable();
            $table->decimal('price');
            $table->string('available_quantity', 10)->nullable();
            $table->text('imageurl')->nullable();
            $table->string('imageurl2', 255)->nullable();
            $table->string('imageurl3', 255)->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('delete_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('productvariants');
    }
}
