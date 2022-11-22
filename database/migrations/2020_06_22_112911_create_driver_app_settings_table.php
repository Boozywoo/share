<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDriverAppSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_app_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('time_show_driver')->default(2);
            $table->boolean('is_see_passeger_phone')->default(true);
            $table->boolean('is_accept_cashless_payment')->default(false);
            $table->boolean('is_change_price')->default(false);
            $table->boolean('was_calling')->default(false);
            $table->boolean('is_cancel')->default(true);
            $table->string('notification')->default('push');
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
        Schema::dropIfExists('driver_app_settings');
    }
}
