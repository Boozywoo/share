<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompamyCarrierCompanyCustomerAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rents', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn(['company_id']);
            $table->integer('company_customer_id')->after('client_id')->nullable()->unsigned();
            $table->foreign('company_customer_id')->references('id')->on('companies')->onDelete('set null');
            $table->integer('company_carrier_id')->after('client_id')->nullable()->unsigned();
            $table->foreign('company_carrier_id')->references('id')->on('companies')->onDelete('set null');
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
            $table->integer('company_id')->nullable()->unsigned()->index();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->dropForeign(['company_customer_id']);
            $table->dropColumn(['company_customer_id']);
            $table->dropForeign(['company_carrier_id']);
            $table->dropColumn(['company_carrier_id']);
        });
    }
}
