<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarousalsTable extends Migration
{
    public function up()
    {
        Schema::create('carousals', function (Blueprint $table) {
            $table->id();
;
            $table->string('shopby')->nullable();
            $table->integer('category_id');
            $table->string('name', 100)->nullable();
            $table->string('name_ar', 100)->nullable();
            $table->text('description')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('imageurl')->nullable();
            $table->text('image_sm')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('delete_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carousals');
    }
}
