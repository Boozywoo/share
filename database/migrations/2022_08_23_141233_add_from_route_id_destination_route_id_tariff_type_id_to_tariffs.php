<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFromRouteIdDestinationRouteIdTariffTypeIdToTariffs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tariffs', function (Blueprint $table) {
            $table->unsignedInteger('route_id')->nullable();
            $table->unsignedInteger('revert_route_id')->nullable();
            $table->unsignedInteger('tariff_direction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tariffs', function (Blueprint $table) {
            $table->dropColumn('route_id');
            $table->dropColumn('revert_route_id');
            $table->dropColumn('tariff_direction_id');
        });
    }
}
