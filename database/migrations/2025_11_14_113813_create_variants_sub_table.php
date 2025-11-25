<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantsSubTable extends Migration
{
    public function up()
    {
        Schema::create('variants_sub', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('variant_id')->nullable();
            $table->string('name', 255)->nullable();
            $table->string('name_ar', 255)->nullable();
            $table->string('color_val', 20)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('delete_status')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('variants_sub');
    }
}
