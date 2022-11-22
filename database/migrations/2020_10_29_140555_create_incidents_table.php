<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('company_id')->unsigned()->index();
            $table->text('comment');
            $table->string('status');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('driver_id')->unsigned()->index();
            $table->integer('incident_template_id')->unsigned()->index();
            $table->integer('department_id')->unsigned()->index();
            $table->date('date_exec')->nullable();
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
        Schema::dropIfExists('incidents');
    }
}
