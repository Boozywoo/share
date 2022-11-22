<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('drivers', function (Blueprint $table) {
            $table->integer('day_before_end_visa')->nullable()->default(null)->after('end_visa');
            $table->integer('day_before_med_day')->nullable()->default(null)->after('med_day');
            $table->integer('day_before_driver_license')->nullable()->default(null)->after('driver_license');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn('day_before_end_visa');
            $table->dropColumn('day_before_med_day');
            $table->dropColumn('day_before_driver_license');
        });

    }
}
