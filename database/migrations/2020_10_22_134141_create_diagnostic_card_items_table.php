<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiagnosticCardItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagnostic_card_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('diagnostic_card_id')->unsigned()->index();
            $table->integer('diagnostic_card_template_item_id')->unsigned()->index();
            $table->string('status');
            $table->text('comment');
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
        Schema::dropIfExists('diagnostic_card_items');
    }
}
