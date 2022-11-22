<?php

use App\Models\OperationalTask\OperationalTask;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationalTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(OperationalTask::getTableName(), function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedInteger('applicant_id');
            $table->unsignedInteger('responsible_id');
            $table->string('subject', 250);
            $table->longText('description');
            $table->string('status');
            $table->timestamps();

            $table->foreign('applicant_id')
                ->references('id')
                ->on(User::getTableName())
                ->cascadeOnDelete();

            $table->foreign('responsible_id')
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
        Schema::drop(OperationalTask::getTableName());
    }
}
