<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveDriversFromReviewActsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('review_acts', function (Blueprint $table) {
            $table->dropColumn(['driver_gives_id','driver_takes_id','driver_checks_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('review_acts', function (Blueprint $table) {
            $table->integer('driver_gives_id')->unsigned()->index();
            $table->integer('driver_takes_id')->unsigned()->index();
            $table->integer('driver_checks_id')->unsigned()->index();
        });
    }
}
