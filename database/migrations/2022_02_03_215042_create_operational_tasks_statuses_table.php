<?php

use App\Models\OperationalTask\OperationalTask;
use App\Models\OperationalTask\OperationalTaskStatus;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationalTasksStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(OperationalTaskStatus::getTableName(), function (Blueprint $table){
            $table->unsignedBigInteger('task_id');
            $table->unsignedInteger('user_id');
            $table->string('status');
            $table->timestamps();

            $table->foreign('task_id')
                ->references('id')
                ->on(OperationalTask::getTableName())
                ->cascadeOnDelete();

            $table->foreign('user_id')
                ->references('id')
                ->on(User::getTableName())
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
        Schema::dropIfExists(OperationalTaskStatus::getTableName());
    }
}
