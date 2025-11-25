<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSearchTermsTable extends Migration
{
    public function up()
    {
        Schema::create('search_terms', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('user_id')->nullable();
            $table->string('keyword', 255)->nullable();
            $table->integer('key_id')->nullable();
            $table->integer('delete_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('search_terms');
    }
}
