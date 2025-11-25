<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponUsersTable extends Migration
{
    public function up()
    {
        Schema::create('coupon_users', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('user_id')->nullable();
            $table->integer('coupon_id')->nullable();
            $table->integer('is_active');
            $table->decimal('sub_price')->nullable();
            $table->decimal('discount_price')->nullable();
            $table->decimal('total_price')->nullable();
            $table->string('token', 55)->nullable();
            $table->string('coupon_code', 55)->nullable();
            $table->decimal('promo_price');
            $table->integer('is_giftwrap');
            $table->text('giftwrap_msg')->nullable();
            $table->integer('address_id')->nullable();
            $table->string('paymenttype', 20)->nullable();
            $table->integer('use_credit');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupon_users');
    }
}
