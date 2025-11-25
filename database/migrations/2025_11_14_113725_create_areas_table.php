<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreasTable extends Migration
{
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('zone_id')->nullable();
            $table->string('name', 255);
            $table->string('name_ar', 100)->nullable();
            $table->string('description', 255)->nullable();
            $table->decimal('delivery_charge')->nullable();
            $table->integer('link_id')->nullable();
            $table->integer('delete_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('areas');
    }
}
