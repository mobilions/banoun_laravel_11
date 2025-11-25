<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('company', 100)->nullable();
            $table->string('company_ar', 100)->nullable();
            $table->string('contact_person', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('support_phone', 20)->nullable();
            $table->string('support_email', 150)->nullable();
            $table->mediumText('location')->nullable();
            $table->mediumText('description')->nullable();
            $table->mediumText('description_ar')->nullable();
            $table->mediumText('header')->nullable();
            $table->mediumText('header_ar')->nullable();
            $table->mediumText('imageurl')->nullable();
            $table->mediumText('facebook')->nullable();
            $table->mediumText('twitter')->nullable();
            $table->mediumText('instagram')->nullable();
            $table->mediumText('whatsapp')->nullable();
            $table->mediumText('google')->nullable();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->timestamps();
            $table->integer('delete_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
