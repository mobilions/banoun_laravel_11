<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
;
            $table->string('company', 100)->nullable();
            $table->string('company_ar', 100)->nullable();
            $table->string('contact_person', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('support_phone', 20)->nullable();
            $table->string('support_email', 150)->nullable();
            $table->text('location')->nullable();
            $table->text('description')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('header')->nullable();
            $table->text('header_ar')->nullable();
            $table->text('imageurl')->nullable();
            $table->text('facebook')->nullable();
            $table->text('twitter')->nullable();
            $table->text('instagram')->nullable();
            $table->text('whatsapp')->nullable();
            $table->text('google')->nullable();
            $table->decimal('giftwrap_price');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('delete_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
