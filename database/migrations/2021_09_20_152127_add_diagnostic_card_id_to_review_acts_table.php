<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDiagnosticCardIdToReviewActsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('review_acts', function (Blueprint $table) {
            $table->integer('diagnostic_card_id')->unsigned()->index();
            $table->foreign('diagnostic_card_id')->references('id')->on('diagnostic_cards');
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
            $table->dropForeign(['diagnostic_card_id']);
            $table->dropColumn('diagnostic_card_id');
        });
    }
}
