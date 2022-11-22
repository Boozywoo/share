<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status')->default('active');
            $table->integer('user_id')->nullable()->unsigned()->index();
            $table->string('source');
            $table->string('small_text');
            $table->text('text');
            $table->tinyInteger('read');
            $table->tinyInteger('denied');
            $table->tinyInteger('new');
            $table->tinyInteger('approved');
            $table->tinyInteger('for_all');
            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
