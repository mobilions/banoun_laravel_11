<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
;
            $table->string('name', 191);
            $table->string('phone', 191)->nullable();
            $table->string('country_code', 16)->nullable();
            $table->string('email', 191)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 191);
            $table->string('role')->nullable();
            $table->string('imgfile', 255)->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->string('otp', 16)->nullable();
            $table->decimal('credit_balance');
            $table->integer('is_verified');
            $table->integer('delete_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
