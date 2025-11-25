<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartmastersTable extends Migration
{
    public function up()
    {
        Schema::create('cartmasters', function (Blueprint $table) {
            $table->integer('id');
            $table->string('order_number', 55)->nullable();
            $table->integer('user_id')->nullable();
            $table->decimal('total');
            $table->decimal('subtotal');
            $table->decimal('tax');
            $table->decimal('delivery');
            $table->decimal('discount');
            $table->decimal('grandtotal');
            $table->integer('totalqty')->nullable();
            $table->integer('address_id')->nullable();
            $table->string('paymenttype', 55)->nullable();
            $table->integer('orderstatus')->nullable();
            $table->string('paymentstatus', 55)->nullable();
            $table->string('paysuccessurl', 100)->nullable();
            $table->text('comments')->nullable();
            $table->text('tracking_url')->nullable();
            $table->string('coupon_code', 55)->nullable();
            $table->integer('is_giftwrap');
            $table->integer('use_credit');
            $table->decimal('promo_price')->nullable();
            $table->decimal('credit_price')->nullable();
            $table->decimal('giftwrap_price')->nullable();
            $table->string('giftwrap_msg', 255)->nullable();
            $table->string('referenceId', 100)->nullable();
            $table->string('PaymentID', 100)->nullable();
            $table->string('TranID', 55)->nullable();
            $table->string('TrackID', 55)->nullable();
            $table->integer('is_deleted');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cartmasters');
    }
}
