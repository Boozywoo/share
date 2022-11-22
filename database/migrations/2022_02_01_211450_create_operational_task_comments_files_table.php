<?php

use App\Models\File;
use App\Models\OperationalTask\OperationalTaskComment;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationalTaskCommentsFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(OperationalTaskComment::FILE_TABLE, function (Blueprint $table) {
            $table->unsignedBigInteger('comment_id');
            $table->unsignedBigInteger('file_id');

            $table->foreign('comment_id')
                ->references('id')
                ->on(OperationalTaskComment::getTableName())
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
        Schema::dropIfExists(OperationalTaskComment::FILE_TABLE);
    }
}
