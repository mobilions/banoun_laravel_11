<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAddressesTable extends Migration
{
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('user_id')->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('country_mobile', 10)->nullable();
            $table->string('landline', 20)->nullable();
            $table->string('country_landline', 10)->nullable();
            $table->string('name', 100)->nullable();
            $table->integer('area_id')->nullable();
            $table->string('area', 100)->nullable();
            $table->string('type', 25)->nullable();
            $table->string('block', 55)->nullable();
            $table->string('street', 100)->nullable();
            $table->string('avenue', 100)->nullable();
            $table->string('building', 100)->nullable();
            $table->string('floor', 55)->nullable();
            $table->string('apartment', 55)->nullable();
            $table->string('additional_info', 100)->nullable();
            $table->string('latitude', 55)->nullable();
            $table->string('longitude', 55)->nullable();
            $table->integer('is_default');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('delete_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_addresses');
    }
}
