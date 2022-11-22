<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSettInDriverApp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('driver_app_settings', function (Blueprint $table) {
            $table->string('is_display_cities')->default(true);
            $table->string('is_display_streets')->default(true);
            $table->string('is_display_stations')->default(true);
            $table->string('is_display_finished_button')->default(true);
            $table->string('is_display_utc')->default(true);
            $table->string('default_code')->default('by');
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
            $table->dropColumn(['is_display_cities']);
            $table->dropColumn(['is_display_streets']);
            $table->dropColumn(['is_display_stations']);
            $table->dropColumn(['is_display_finished_button']);
            $table->dropColumn(['is_display_utc']);
            $table->dropColumn(['default_code']);
        });
    }
}
