<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStreetHouseApartFieldsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('address_from_street')->nullable();
            $table->string('address_from_house')->nullable();
            $table->string('address_from_building')->nullable();
            $table->string('address_from_apart')->nullable();
            $table->string('address_to_street')->nullable();
            $table->string('address_to_house')->nullable();
            $table->string('address_to_building')->nullable();
            $table->string('address_to_apart')->nullable();
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
            $table->dropColumn(['address_to_street', 'address_to_house', 'address_to_building', 'address_to_apart',
                'address_from_street', 'address_from_house', 'address_from_building', 'address_from_apart']);
        });
    }
}
