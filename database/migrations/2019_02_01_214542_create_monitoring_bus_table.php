<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonitoringBusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitoring_bus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bus_id');
            $table->string('phone');
            $table->string('password');
            $table->float('latitude', 13, 8);
            $table->float('longitude', 13, 8);
            $table->float('speed')->nullable();
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
        Schema::dropIfExists('monitoring_bus');
    }
}
