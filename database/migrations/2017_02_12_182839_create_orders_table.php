<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tour_id')->unsigned()->index()->nullable();
            $table->foreign('tour_id')->references('id')->on('tours')->onDelete('set null');
            $table->integer('client_id')->unsigned()->index()->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            $table->integer('operator_id')->unsigned()->nullable();
            $table->foreign('operator_id')->references('id')->on('users')->onDelete('set null');
            $table->integer('coupon_id')->unsigned()->nullable();
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('set null');
            $table->integer('station_from_id')->unsigned()->nullable();
            $table->foreign('station_from_id')->references('id')->on('stations')->onDelete('set null');
            $table->integer('station_to_id')->unsigned()->nullable();
            $table->foreign('station_to_id')->references('id')->on('stations')->onDelete('set null');
            $table->decimal('price', 8, 2);
            $table->integer('count_places');
            $table->string('status');
            $table->string('type');
            $table->string('source');
            $table->boolean('confirm');
            $table->boolean('pull');
            $table->text('comment');
            $table->text('old_places');
            $table->boolean('social_status_confirm')->nullable();
            $table->boolean('places_with_number');
            $table->integer('appearance')->nullable();
            $table->time('station_from_time');
            $table->time('station_to_time');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
