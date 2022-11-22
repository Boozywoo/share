<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDiscountsTypesColumnsInRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->boolean('discount_return_ticket_type')->default(false)->after('discount_return_ticket')
                ->comment('Тип скидки на обратный билет, false - в абсолютном выражении, true - в процентном выражении');
            $table->boolean('discount_child_type')->default(false)->after('discount_child')
                ->comment('Тип скидки на ребенка, false - в абсолютном выражении, true - в процентном выражении');
            $table->boolean('bonus_agent_type')->default(false)->after('bonus_agent')
                ->comment('Тип комиссии агента, false - в абсолютном выражении, true - в процентном выражении');
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
            $table->dropColumn(['discount_return_ticket_type', 'discount_child_type', 'bonus_agent_type']);
        });
    }
}
