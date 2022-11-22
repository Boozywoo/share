<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSettFioInDriverApp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('driver_app_settings', function (Blueprint $table) {
            $table->string('is_display_first_name')->default(true);
            $table->string('is_display_middle_name')->default(true);
            $table->string('is_display_last_name')->default(true);
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
            $table->dropColumn(['is_display_first_name']);
            $table->dropColumn(['is_display_middle_name']);
            $table->dropColumn(['is_display_last_name']);
        });
    }
}
