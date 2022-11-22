<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDiagnosticCardTemplateIdToReviewActTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('review_act_templates', function (Blueprint $table) {
            $table->integer('diagnostic_card_template_id')->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('review_act_templates', function (Blueprint $table) {
            $table->dropColumn('diagnostic_card_template_id');
        });
    }
}
