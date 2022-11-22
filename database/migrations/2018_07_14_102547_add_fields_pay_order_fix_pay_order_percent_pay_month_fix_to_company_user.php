<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsPayOrderFixPayOrderPercentPayMonthFixToCompanyUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_user', function (Blueprint $table) {
            $table->float('pay_order_fix')->nullable();
            $table->integer('pay_order_percent')->nullable();
            $table->integer('pay_month_fix')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_user', function (Blueprint $table) {
            $table->dropColumn(['pay_order_fix', 'pay_order_percent', 'pay_month_fix']);
        });
    }
}
