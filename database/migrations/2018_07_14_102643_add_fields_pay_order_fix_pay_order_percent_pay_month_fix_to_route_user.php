<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsPayOrderFixPayOrderPercentPayMonthFixToRouteUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('route_user', function (Blueprint $table) {
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
        Schema::table('route_user', function (Blueprint $table) {
            $table->dropColumn(['pay_order_fix', 'pay_order_percent', 'pay_month_fix']);
        });
    }
}
