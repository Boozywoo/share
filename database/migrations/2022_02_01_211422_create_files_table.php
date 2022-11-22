<?php

use Illuminate\Support\Facades\Schema;
use App\Models\File;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(File::getTableName(), function (Blueprint $table){
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('original_name');
            $table->string('src');
            $table->string('size');
            $table->string('extension');
            $table->string('type');
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
        Schema::dropIfExists(File::getTableName());
    }
}
