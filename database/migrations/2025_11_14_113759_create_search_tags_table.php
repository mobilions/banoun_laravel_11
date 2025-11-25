<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSearchTagsTable extends Migration
{
    public function up()
    {
        Schema::create('search_tags', function (Blueprint $table) {
            $table->integer('id');
            $table->string('title', 200)->nullable();
            $table->string('title_ar', 255)->nullable();
            $table->integer('count');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('delete_status')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('search_tags');
    }
}
