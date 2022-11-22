<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTariffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tariffs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bus_type_id')->unsigned()->index()->nullable();
            $table->foreign('bus_type_id')->references('id')->on('bus_types')->onDelete('cascade');
            $table->integer('agreement_id')->unsigned()->index()->nullable();
            $table->foreign('agreement_id')->references('id')->on('agreements')->onDelete('cascade');
            $table->string('type');
            $table->integer('min')->nullable();
            $table->integer('max')->nullable();
            $table->float('cost')->nullable();
            $table->string('status', 10)->nullable();
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
        Schema::dropIfExists('tariffs');
    }
}
