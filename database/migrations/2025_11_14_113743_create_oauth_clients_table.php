<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthClientsTable extends Migration
{
    public function up()
    {
        Schema::create('oauth_clients', function (Blueprint $table) {
            $table->id();
;
            $table->integer('user_id')->nullable();
            $table->string('name', 191);
            $table->string('secret', 100)->nullable();
            $table->string('provider', 191)->nullable();
            $table->text('redirect');
            $table->integer('personal_access_client');
            $table->integer('password_client');
            $table->integer('revoked');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('oauth_clients');
    }
}
