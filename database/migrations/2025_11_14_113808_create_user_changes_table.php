<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserChangesTable extends Migration
{
    public function up()
    {
        Schema::create('user_changes', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('user_id')->nullable();
            $table->string('field', 100)->nullable();
            $table->string('value', 255)->nullable();
            $table->integer('is_verified');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_changes');
    }
}
