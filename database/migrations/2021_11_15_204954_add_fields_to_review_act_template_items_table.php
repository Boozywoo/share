<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToReviewActTemplateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('review_act_template_items', function (Blueprint $table) {
            $table->tinyInteger('is_photo')->default(0);
            $table->tinyInteger('is_comment')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('review_act_template_items', function (Blueprint $table) {
            $table->dropColumn(['is_photo', 'is_comment']);
        });
    }
}
