<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLastNameGenderDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->string('last_name')->after('full_name')->nullable();
            $table->string('middle_name')->after('last_name')->nullable();
            $table->integer('doc_type')->after('company_id')->nullable();
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
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn(['last_name', 'middle_name', 'doc_type', 'doc_number', 'gender', 'country_id']);
        });
    }
}
