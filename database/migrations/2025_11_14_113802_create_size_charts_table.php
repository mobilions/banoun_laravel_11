<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSizeChartsTable extends Migration
{
    public function up()
    {
        Schema::create('size_charts', function (Blueprint $table) {
            $table->integer('id');
            $table->string('name', 30);
            $table->string('type', 30)->nullable();
            $table->text('description')->nullable();
            $table->text('description_ar')->nullable();
            $table->integer('delete_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('size_charts');
    }
}
