<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStartMinFinishMinOrderPlaces extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_places', function (Blueprint $table) {
            $table->integer('start_min')->nullable();
            $table->integer('finish_min')->nullable();
            $table->dropColumn(['station_from_time','station_to_time']);
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
            $table->dropColumn(['start_min','finish_min']);
            $table->dateTime('station_from_time')->nullable();
            $table->dateTime('station_to_time')->nullable();
        });
    }
}
