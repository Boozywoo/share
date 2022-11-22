<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRouteStationPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_station_price', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('route_id')->unsigned()->index();
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
            $table->integer('station_from_id')->unsigned()->index();
            $table->foreign('station_from_id')->references('id')->on('stations')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('station_to_id')->unsigned()->index();
            $table->foreign('station_to_id')->references('id')->on('stations')->onUpdate('cascade')->onDelete('cascade');
            $table->float('price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('route_station_price');
    }
}
