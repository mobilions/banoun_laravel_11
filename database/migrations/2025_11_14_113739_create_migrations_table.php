<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMigrationsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('migrations')) {
            return;
        }

        Schema::create('migrations', function (Blueprint $table) {
            $table->integer('id');
            $table->string('migration', 191);
            $table->integer('batch');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('migrations');
    }
}
