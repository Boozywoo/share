<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToBuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('buses', function(Blueprint $table)
        {
            $table->integer('day_before_insurance')->null();
            $table->integer('day_before_revision')->null();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('buses', function(Blueprint $table){
            $table->dropColumn('day_before_insurance');
            $table->dropColumn('day_before_revision');
        });
    }
}
