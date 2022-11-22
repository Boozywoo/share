<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTitleTrField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->string('name_tr')->after('name');
        });
        Schema::table('cities', function (Blueprint $table) {
            $table->string('name_tr')->after('name');
        });
        Schema::table('stations', function (Blueprint $table) {
            $table->string('name_tr')->after('name');
        });
        Schema::table('buses', function (Blueprint $table) {
            $table->string('name_tr')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->dropColumn('name_tr');
        });
        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn('name_tr');
        });
        Schema::table('stations', function (Blueprint $table) {
            $table->dropColumn('name_tr');
        });
        Schema::table('buses', function (Blueprint $table) {
            $table->dropColumn('name_tr');
        });
    }
}
