<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrepaidToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('partial_prepaid')->nullable()->after('type_pay')->comment('Оплата была произведена частично');
            $table->decimal('prepaid_price', 8, 2)->nullable()->after('partial_prepaid')->comment('Сумма частичной предоплаты');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['partial_prepaid', 'prepaid_price']);
        });
    }
}
