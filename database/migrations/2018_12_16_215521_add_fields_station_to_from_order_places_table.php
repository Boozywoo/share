<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsStationToFromOrderPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_places', function (Blueprint $table) {
            $table->integer('station_from_id')->nullable();
            $table->integer('station_to_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_places', function (Blueprint $table) {
            $table->dropColumn(['station_from_id','station_to_id']);
        });
    }
}
