<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKmAndFuelToDiagnosticCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('diagnostic_cards', function (Blueprint $table) {
            $table->integer('km')->unsigned();
            $table->integer('fuel')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('diagnostic_cards', function (Blueprint $table) {
            $table->dropColumn('km');
            $table->dropColumn('fuel');
        });
    }
}
