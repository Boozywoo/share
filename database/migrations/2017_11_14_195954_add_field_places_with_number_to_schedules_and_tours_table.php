<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldPlacesWithNumberToSchedulesAndToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->boolean('reservation_by_place');
        });
        Schema::table('tours', function (Blueprint $table) {
            $table->boolean('reservation_by_place');
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
            $table->dropColumn('places_with_number');
        });
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn('places_with_number');
        });
    }
}
