<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepairSparePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repair_spare_parts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('spare_part_id');
            $table->unsignedInteger('repair_id');
            $table->string('status');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('count')->default(1);
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
        Schema::dropIfExists('repair_spare_parts');
    }
}
