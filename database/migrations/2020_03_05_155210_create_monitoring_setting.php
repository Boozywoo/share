<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonitoringSetting extends Migration
{
    public function up()
    {
        Schema::create('monitoring_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('high_speed')->default(100);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('monitoring_settings');
    }
}
