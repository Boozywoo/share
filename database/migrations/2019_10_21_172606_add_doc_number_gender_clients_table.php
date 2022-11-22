<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDocNumberGenderClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->integer('doc_type')->after('passport')->nullable();
            $table->string('doc_number')->after('doc_type')->nullable();
            $table->string('gender')->after('company_id')->nullable();
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
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['doc_type', 'doc_number', 'gender', 'country_id']);
        });
    }
}
