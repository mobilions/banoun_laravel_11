<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryOptionsTable extends Migration
{
    public function up()
    {
        Schema::create('delivery_options', function (Blueprint $table) {
            $table->integer('id');
            $table->string('name', 150)->nullable();
            $table->string('name_ar', 255)->nullable();
            $table->text('imageurl')->nullable();
            $table->string('icon', 255)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('delete_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_options');
    }
}
