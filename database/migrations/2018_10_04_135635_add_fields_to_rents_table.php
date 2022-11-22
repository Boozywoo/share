<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToRentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rents', function (Blueprint $table) {
            $table->string('address')->after('add_km');
            $table->string('city')->after('add_km');
            $table->boolean('is_legal_entity')->after('add_km');
            $table->string('cnt_passengers')->after('add_km');
            $table->string('type_pay')->after('add_km');
            $table->string('is_pay')->after('add_km');
            $table->integer('company_id')->after('add_km')->nullable()->unsigned()->index();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->integer('operator_id')->after('add_km')->nullable()->unsigned()->index();
            $table->foreign('operator_id')->references('id')->on('users')->onDelete('cascade');
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
            $table->dropIndex(['operator_id', 'company_id']);
            $table->dropForeign(['operator_id', 'company_id']);
            $table->dropColumn(['address', 'city', 'is_legal_entity', 'cnt_passengers', 'type_pay', 'is_pay','operator_id', 'company_id']);
        });
    }
}
