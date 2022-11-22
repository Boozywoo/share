<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBgImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bg_images', function (Blueprint $table) {
            $table->increments('id')->unique();
            // $table->
            $table->integer('user_id')
                ->unsigned()
                ->nullable()
                ->index()
                ->unique();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');;
            $table->text('ui_adm_img')->nullable();
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
        Schema::table('bg_images', function (Blueprint $table) {
            Schema::dropIfExists('bg_images');
        });
    }
}
