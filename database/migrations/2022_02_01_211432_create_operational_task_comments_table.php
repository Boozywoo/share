<?php

use App\Models\OperationalTask\OperationalTask;
use App\Models\OperationalTask\OperationalTaskComment;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationalTaskCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(OperationalTaskComment::getTableName(), function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('task_id');
            $table->unsignedInteger('user_id');
            $table->longText('comment');
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
        Schema::dropIfExists(OperationalTaskComment::getTableName());
    }
}
