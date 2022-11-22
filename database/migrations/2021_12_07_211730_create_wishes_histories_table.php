<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWishesHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wishes_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('wishes_id')->unsigned()->index();
            $table->foreign('wishes_id')->references('id')->on('wishes')->onDelete('cascade');
            $table->string('action');
            $table->string('instance')->nullable();
            $table->string('instance_id')->nullable();
            $table->string('text')->nullable();
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
        Schema::dropIfExists('wishes_histories');
    }
}
