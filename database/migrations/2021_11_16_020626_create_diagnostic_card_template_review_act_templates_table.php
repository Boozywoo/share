<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiagnosticCardTemplateReviewActTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagnostic_card_template_review_act_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('diagnostic_card_template_id');
            $table->unsignedInteger('review_act_template_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diagnostic_card_template_review_act_templates');
    }
}
