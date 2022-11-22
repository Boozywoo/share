<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFromToCitiesToRentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rents', function (Blueprint $table) {
            $table->dropColumn(['city']);
            $table->integer('to_city_id')->after('is_legal_entity')->nullable()->unsigned();
            $table->foreign('to_city_id')->references('id')->on('cities')->onDelete('set null');
            $table->integer('from_city_id')->after('is_legal_entity')->nullable()->unsigned();
            $table->foreign('from_city_id')->references('id')->on('cities')->onDelete('set null');
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
            $table->string('city');
            $table->dropForeign(['to_city_id']);
            $table->dropForeign(['from_city_id']);
            $table->dropColumn(['to_city_id', 'from_city_id']);
        });
    }
}
