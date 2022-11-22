<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFieldsBusIdDriverIdTableTours extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('ALTER TABLE `tours` MODIFY `bus_id` INTEGER UNSIGNED NULL;');
        \DB::statement('ALTER TABLE `tours` MODIFY `driver_id` INTEGER UNSIGNED NULL;');
        \DB::statement('ALTER TABLE `tours` MODIFY `bus_main_id` INTEGER UNSIGNED NULL;');
        Schema::table('tours', function (Blueprint $table) {
            $table->boolean('is_rent')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*\DB::statement('ALTER TABLE `tours` MODIFY `bus_id` INTEGER UNSIGNED NOT NULL;');
        \DB::statement('ALTER TABLE `tours` MODIFY `driver_id` INTEGER UNSIGNED NOT NULL;');
        \DB::statement('ALTER TABLE `tours` MODIFY `bus_main_id` INTEGER UNSIGNED NOT NULL;');*/
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn('is_rent');
        });
    }
}
