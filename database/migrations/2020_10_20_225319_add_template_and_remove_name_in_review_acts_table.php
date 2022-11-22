<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTemplateAndRemoveNameInReviewActsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('review_acts', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->integer('review_act_template_id')->unsigned()->index();
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
            $table->string('name');
            $table->dropColumn('review_act_template_id');

        });
    }
}
