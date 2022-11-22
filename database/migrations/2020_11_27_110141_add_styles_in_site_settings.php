<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStylesInSiteSettings extends Migration
{
    public function up()
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('button_color');
            $table->string('background_color');
            $table->string('font_color');
            $table->tinyInteger('font_size');
            $table->float('opacity');
            $table->tinyInteger('border_radius');
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
        Schema::dropIfExists('site_settings');
    }
}
