<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimeHiddenTourFrontToRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->integer('time_hidden_tour_front')->default(0)->comment('Время, за которое отключать бронирование на сайте (мин)');
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
            $table->dropColumn(['time_hidden_tour_front']);
        });
    }
}
