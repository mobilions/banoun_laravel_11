<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
;
            $table->text('imageurl')->nullable();
            $table->text('image_sm')->nullable();
            $table->decimal('order_id')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('delete_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('banners');
    }
}
