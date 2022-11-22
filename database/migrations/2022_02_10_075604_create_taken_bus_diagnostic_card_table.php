<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTakenBusDiagnosticCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taken_bus_diagnostic_card', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_taken_bus_id');
            $table->unsignedInteger('diagnostic_card_id');
            $table->timestamps();

            $table->foreign('user_taken_bus_id')->references('id')->on('user_taken_buses');
            $table->foreign('diagnostic_card_id')->references('id')->on('diagnostic_cards');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taken_bus_diagnostic_card');
    }
}
