<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('product_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('variant_id')->nullable();
            $table->integer('size_id')->nullable();
            $table->integer('qty')->nullable();
            $table->decimal('actual_price')->nullable();
            $table->decimal('offer_price');
            $table->decimal('total_price')->nullable();
            $table->integer('master_id')->nullable();
            $table->integer('carted');
            $table->integer('from_wishlist')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('delete_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
