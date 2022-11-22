<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAgrementIdTariffId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rents', function (Blueprint $table) {
            $table->integer('agreement_id')->unsigned()->after('company_customer_id')->index()->nullable();
            $table->foreign('agreement_id')->references('id')->on('agreements')->onDelete('set null');
            $table->integer('tariff_id')->unsigned()->after('company_customer_id')->index()->nullable();
            $table->foreign('tariff_id')->references('id')->on('tariffs')->onDelete('set null');
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
            $table->dropForeign(['agreement_id', 'tariff_id']);
            $table->dropColumn(['agreement_id', 'tariff_id']);
        });
    }
}
