<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWishlistsTable extends Migration
{
    public function up()
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('product_id')->nullable();
            $table->integer('variant_id')->nullable();
            $table->integer('size_id')->nullable();
            $table->integer('qty');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('status');
            $table->integer('delete_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wishlists');
    }
}
