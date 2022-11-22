<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeBusRentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rents', function (Blueprint $table) {
            $table->integer('bus_type_id')->unsigned()->index()->nullable()->after('client_id');
            $table->foreign('bus_type_id')->references('id')->on('bus_types')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rents', function (Blueprint $table) {
            $table->dropForeign(['bus_type_id']);
            $table->dropColumn(['bus_type_id']);
        });
    }
}
