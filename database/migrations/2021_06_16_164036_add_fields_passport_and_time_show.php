<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsPassportAndTimeShow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('driver_app_settings', function (Blueprint $table) {
            $table->integer('time_click_driver')->default(2);
            $table->boolean('is_see_passeger_passport')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('driver_app_settings', function (Blueprint $table) {
            $table->dropColumn(['time_click_driver', 'is_see_passeger_passport']);
        });
    }
}
