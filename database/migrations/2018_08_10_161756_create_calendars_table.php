<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendars', function (Blueprint $table) {
            $table->increments('id');

            // Relationships.
            $table->unsignedInteger('google_account_id');
            $table->foreign('google_account_id')
                  ->references('id')->on('google_accounts')
                  ->onDelete('cascade');

            // Data.
            $table->string('google_id');
            $table->string('name');
            $table->string('color');
            $table->string('timezone');

            // Timestamps.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendars');
    }
}
