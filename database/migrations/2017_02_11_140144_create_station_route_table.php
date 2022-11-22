<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStationRouteTable extends Migration
{
    public function up()
    {
        Schema::create('route_station', function (Blueprint $table) {
            $table->integer('station_id')->unsigned()->index();
            $table->foreign('station_id')->references('id')->on('stations')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('route_id')->unsigned()->index();
            $table->foreign('route_id')->references('id')->on('routes')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('order');
            $table->integer('time');
            $table->integer('interval');
            $table->primary(['station_id', 'route_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('route_station');
    }
}
