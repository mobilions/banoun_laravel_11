<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->integer('id');
            $table->string('coupon_type')->nullable();
            $table->integer('coupon_type_id')->nullable();
            $table->string('price_type')->nullable();
            $table->string('coupon_val', 30)->nullable();
            $table->string('coupon_code', 100)->nullable();
            $table->string('coupon_code_ar', 100)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('delete_status')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
