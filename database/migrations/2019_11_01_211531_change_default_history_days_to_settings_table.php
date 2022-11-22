<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDefaultHistoryDaysToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {

//            Drop column because:
//
//            Only the following column types can be "changed": bigInteger, binary, boolean, date,
//            dateTime, dateTimeTz, decimal, integer, json, longText, mediumText, smallInteger,
//            string, text, time, unsignedBigInteger, unsignedInteger and unsignedSmallInteger.

            $table->dropColumn('history_days');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->integer('history_days')->default(1000);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('history_days');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->integer('history_days')->default(30);
        });
    }
}
