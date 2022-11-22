<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RedactInterfaceSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('interface_settings', function (Blueprint $table) {
            $table->renameColumn('font_color', 'theme_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('interface_settings', function (Blueprint $table) {
            $table->renameColumn('theme_name', 'font_color');
        });
    }
}
