<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->string('integration_uid', 100)->after('shift')->nullable();
            $table->integer('integration_id')->after('shift')->unsigned()->index()->nullable();
            $table->foreign('integration_id')->references('id')->on('integrations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropForeign(['integration_id']);
            $table->dropColumn(['integration_uid', 'integration_id']);
        });
    }
}
