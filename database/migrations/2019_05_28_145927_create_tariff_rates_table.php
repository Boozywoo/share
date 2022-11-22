<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTariffRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tariff_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tariff_id')->unsigned()->index()->nullable();
            $table->foreign('tariff_id')->references('id')->on('tariffs')->onDelete('cascade');
            $table->integer('min')->nullable();
            $table->integer('max')->nullable();
            $table->float('cost')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tariff_rates');
    }
}
