<?php

use App\Models\Wishes;
use App\Models\WishesFile;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWishesFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(WishesFile::getTableName(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('wishes_id');
            $table->string('name');
            $table->string('original_name');
            $table->string('src');
            $table->string('size');
            $table->string('extension');
            $table->string('type');
            $table->timestamps();

            $table->foreign('wishes_id')
                ->references('id')
                ->on(Wishes::getTableName())
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
        $files = WishesFile::all();

        foreach ($files as $file) {
            Storage::delete($file->src);
        }

        Schema::dropIfExists(WishesFile::getTableName());
    }
}
