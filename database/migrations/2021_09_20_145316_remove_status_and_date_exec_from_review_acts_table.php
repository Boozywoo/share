<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveStatusAndDateExecFromReviewActsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('review_acts', function (Blueprint $table) {
            $table->dropColumn(['status', 'date_exec']);
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
            $table->string('status');
            $table->date('date_exec');
        });
    }
}
