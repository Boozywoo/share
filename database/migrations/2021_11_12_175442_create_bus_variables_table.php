<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusVariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bus_variables', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('odometer');
            $table->unsignedInteger('fuel');
            $table->unsignedInteger('imageable_id');
            $table->string('imageable_type');
            $table->unsignedInteger('bus_id');
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
        Schema::dropIfExists('bus_variables');
    }
}
