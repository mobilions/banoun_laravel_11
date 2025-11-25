<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQaDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('qa_details', function (Blueprint $table) {
            $table->integer('id');
            $table->string('title', 255)->nullable();
            $table->text('content')->nullable();
            $table->string('title_ar', 255)->nullable();
            $table->text('content_ar')->nullable();
            $table->string('type', 16)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('delete_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('qa_details');
    }
}
