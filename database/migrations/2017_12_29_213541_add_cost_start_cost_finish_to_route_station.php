<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCostStartCostFinishToRouteStation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('route_station', function (Blueprint $table) {
            $table->integer('cost_start')->default(0);
            $table->integer('cost_finish')->default(0);
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
            $table->dropColumn(['cost_start', 'cost_finish']);
        });
    }
}
