<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRoutesTablePhoneCodeAndCurrencyIdAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->string('phone_code', 6)->default(config('app.input_code'));

            $defaultCurrency = \App\Models\Currency::query()->where('alfa', config('app.currency_id'))->first();
            $table->integer('currency_id')->unsigned()->default($defaultCurrency->id ?? 1);
            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->dropColumn('phone_code');
            $table->dropColumn('currency_id');
        });
    }
}
