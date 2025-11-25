<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopcollectionsTable extends Migration
{
    public function up()
    {
        Schema::create('topcollections', function (Blueprint $table) {
            $table->id();
;
            $table->integer('category_id');
            $table->string('shopby');
            $table->string('redirect_type', 55)->nullable();
            $table->string('redirect_by', 55)->nullable();
            $table->string('name', 100)->nullable();
            $table->string('name_ar', 100)->nullable();
            $table->text('description')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('imageurl')->nullable();
            $table->string('type', 55)->nullable();
            $table->integer('categoryId')->nullable();
            $table->text('image_sm')->nullable();
            $table->integer('grid')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('delete_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('topcollections');
    }
}
