<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderPlaceSaleTable extends Migration
{
    public function up()
    {
        Schema::create('order_place_sale', function (Blueprint $table) {
            $table->integer('order_place_id')->unsigned()->index();
            $table->foreign('order_place_id')->references('id')->on('order_places')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('sale_id')->unsigned()->index();
            $table->foreign('sale_id')->references('id')->on('sales')->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('old_price', 8, 2);
            $table->decimal('new_price', 8, 2);
            $table->primary(['order_place_id', 'sale_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_place_sale');
    }
}
