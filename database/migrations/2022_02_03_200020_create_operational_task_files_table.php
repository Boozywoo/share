<?php

use App\Models\File;
use App\Models\OperationalTask\OperationalTask;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationalTaskFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(OperationalTask::FILE_TABLE, function (Blueprint $table){
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('file_id');

            $table->foreign('task_id')
                ->references('id')
                ->on(OperationalTask::getTableName())
                ->cascadeOnDelete();

            $table->foreign('file_id')
                ->references('id')
                ->on(File::getTableName())
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(OperationalTask::FILE_TABLE);
    }
}
