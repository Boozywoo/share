<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimeLimitInSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->integer('time_limit_pay')->default(10);
        });
        Schema::table('routes', function (Blueprint $table) {
            $table->dropColumn(['time_limit_pay']);
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
            $table->dropColumn(['time_limit_pay']);
        });
        Schema::table('routes', function (Blueprint $table) {
            $table->integer('time_limit_pay')->nullable();
        });
    }
}
