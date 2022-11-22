<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPersonalDataFieldsToOrderPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_places', function (Blueprint $table) {
            $table->string('phone')->after('passport')->nullable();
            $table->string('email')->after('phone')->nullable();
            $table->integer('doc_type')->after('passport')->nullable();
            $table->string('doc_number')->after('doc_type')->nullable();
            $table->string('gender')->after('doc_number')->nullable();
            $table->integer('country_id')->after('gender')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_places', function (Blueprint $table) {
            $table->dropColumn(['phone', 'email', 'doc_type', 'doc_number', 'gender', 'country_id']);
        });
    }
}
