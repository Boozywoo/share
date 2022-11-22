<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rents', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('is_meet_airport');
            $table->integer('chair_child')->nullable();
            $table->integer('booster')->nullable();
            $table->integer('wheelchair')->nullable();
            $table->integer('time_wait')->nullable();
            $table->integer('add_km')->nullable();
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
        \DB::table('rents')->delete();
        Schema::dropIfExists('rents');
    }
}
