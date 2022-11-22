<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldCountPassport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('driver_app_settings', function (Blueprint $table) {
            $table->integer('count_of_passport_digits')->default(4);
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
            $table->dropColumn(['count_of_passport_digits']);
        });
    }
}
