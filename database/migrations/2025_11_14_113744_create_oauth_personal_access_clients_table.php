<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthPersonalAccessClientsTable extends Migration
{
    public function up()
    {
        Schema::create('oauth_personal_access_clients', function (Blueprint $table) {
            $table->id();
;
            $table->integer('client_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('oauth_personal_access_clients');
    }
}
