<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepairOrderOutfitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repair_order_outfits', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('repair_id')->unique();
            $table->unsignedInteger('creator_id');
            $table->date('date_from');
            $table->unsignedInteger('odometer');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('repair_id')->references('id')->on('repairs')->onDelete('cascade');
            $table->foreign('creator_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repair_order_outfits');
    }
}
