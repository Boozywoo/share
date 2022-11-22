<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agreements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number');
            $table->date('date')->nullable();
            $table->string('description');
            $table->integer('customer_company_id')->unsigned()->index()->nullable();
            $table->foreign('customer_company_id')->references('id')->on('companies')->onDelete('set null');
            $table->integer('service_company_id')->unsigned()->index()->nullable();
            $table->foreign('service_company_id')->references('id')->on('companies')->onDelete('set null');
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
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
        Schema::dropIfExists('agreements');
    }
}
