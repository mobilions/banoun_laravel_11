<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthAccessTokensTable extends Migration
{
    public function up()
    {
        Schema::create('oauth_access_tokens', function (Blueprint $table) {
            $table->string('id', 100);
            $table->integer('user_id')->nullable();
            $table->integer('client_id');
            $table->string('name', 191)->nullable();
            $table->text('scopes')->nullable();
            $table->integer('revoked');
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('oauth_access_tokens');
    }
}
