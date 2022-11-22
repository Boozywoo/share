<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('schedule_id')->unsigned()->index()->nullable();
            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('set null');
            $table->integer('route_id')->unsigned()->index();
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
            $table->integer('bus_id')->unsigned()->index();
            $table->foreign('bus_id')->references('id')->on('buses')->onDelete('cascade');
            $table->integer('bus_main_id')->unsigned()->index();
            $table->foreign('bus_main_id')->references('id')->on('buses')->onDelete('cascade');
            $table->integer('driver_id')->unsigned()->index();
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
            $table->date('date_start');
            $table->time('time_start');
            $table->time('time_finish');
            $table->decimal('price', 8, 2);
            $table->string('status');
            $table->string('type_driver');
            $table->boolean('shift');
            $table->text('comment');
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
        Schema::dropIfExists('tours');
    }
}
