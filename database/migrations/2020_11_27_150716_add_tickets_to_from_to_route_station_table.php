<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTicketsToFromToRouteStationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('route_station', function (Blueprint $table) {
            $table->renameColumn('tickets', 'tickets_from');
            $table->boolean('tickets_to')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('route_station', function (Blueprint $table) {
            $table->renameColumn('tickets_from', 'tickets');
            $table->dropColumn(['tickets_to']);
        });
    }
}
