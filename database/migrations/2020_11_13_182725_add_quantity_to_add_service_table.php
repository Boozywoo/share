<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQuantityToAddServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_add_service', function (Blueprint $table) {
            $table->tinyInteger('quantity')->default(1)->after('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::order_add_service('order_add_service', function (Blueprint $table) {
            $table->dropColumn(['quantity']);
        });
    }
}
