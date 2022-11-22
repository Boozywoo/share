<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRouteUserTable extends Migration
{
    public function up()
    {
        Schema::create('route_user', function (Blueprint $table) {
            $table->integer('route_id')->unsigned()->index();
            $table->foreign('route_id')->references('id')->on('routes')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['route_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('route_user');
    }
}
