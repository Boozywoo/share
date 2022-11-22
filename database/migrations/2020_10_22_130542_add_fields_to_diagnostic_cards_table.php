<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToDiagnosticCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('diagnostic_cards', function (Blueprint $table) {
            $table->string('sap_number');
            $table->string('reg_number');
            $table->integer('contractor_id')->unsigned()->index();
            $table->integer('master_id')->unsigned()->index();
            $table->integer('diagnostic_card_template_id')->unsigned()->index();
            $table->date('date_scheduled')->nullable();
            $table->text('notes')->nullable();
            $table->dropColumn('name');
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
            $table->dropColumn(['sup_number','reg_number','date_scheduled','notes','contractor_id','master_id','diagnostic_card_template_id']);
            $table->string('name');

        });

    }
}
