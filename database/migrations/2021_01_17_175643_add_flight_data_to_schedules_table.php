<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFlightDataToSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->string('flight_ac_code')->nullable();
            $table->string('flight_number')->nullable();
            $table->time('flight_time')->nullable();
            $table->time('flight_offset')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */

    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('flight_ac_code', 'flight_number', 'flight_time', 'flight_offset');
        });
    }
}
