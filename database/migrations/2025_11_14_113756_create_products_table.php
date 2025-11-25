<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
;
            $table->integer('category_id');
            $table->integer('subcategory_id');
            $table->integer('brand_id');
            $table->string('name', 100)->nullable();
            $table->string('name_ar', 100)->nullable();
            $table->text('description')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('more_info')->nullable();
            $table->text('more_info_ar')->nullable();
            $table->text('imageurl')->nullable();
            $table->string('imageurl2', 255)->nullable();
            $table->string('imageurl3', 255)->nullable();
            $table->decimal('price');
            $table->decimal('price_offer');
            $table->integer('percentage_discount');
            $table->integer('is_newarrival');
            $table->integer('is_trending');
            $table->integer('is_recommended');
            $table->integer('is_topsearch')->nullable();
            $table->string('searchtag_id', 255)->nullable();
            $table->integer('search_count');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('accept_status');
            $table->integer('delete_status');
            $table->string('min_age', 10)->nullable();
            $table->string('max_age', 10)->nullable();
            $table->string('colors', 50)->nullable();
            $table->string('size', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
