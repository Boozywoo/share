<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShowAllDirectionsPassengers extends Migration
{
    public function up()
    {
        Schema::table('driver_app_settings', function (Blueprint $table) {
            $table->boolean('is_show_both_directions')->default(false);
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
            $table->dropColumn(['is_show_both_directions']);
        });
    }
}
