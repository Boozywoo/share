<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDiagnosticCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('diagnostic_cards', function (Blueprint $table) {
            $table->dropColumn(['status','date_exec','sap_number','reg_number','contractor_id','master_id','km','fuel']);
            $table->unsignedInteger('user_id');

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
            $table->string('status');
            $table->date('date_exec');
            $table->string('sap_number');
            $table->string('reg_number');
            $table->unsignedInteger('contractor_id')->index();
            $table->unsignedInteger('master_id')->index();
            $table->unsignedInteger('km');
            $table->unsignedInteger('fuel');

            $table->dropColumn('user_id');
        });
    }
}
