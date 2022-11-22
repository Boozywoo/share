<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMapInDriverApp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('driver_app_settings', function(Blueprint $table)
        {
            $table->integer('is_see_map')->default(false);
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
        Schema::table('driver_app_settings', function(Blueprint $table){
            $table->dropColumn('is_see_map');
        });
    }
}
