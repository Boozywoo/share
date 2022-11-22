<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBgImagesIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('bg_image_id')
                ->unsigned()
                ->index()
                ->unique()
                ->nullable()
                ->after('interface_setting_id');
            $table->foreign('bg_image_id')->references('id')->on('bg_images')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['bg_image_id']);
            $table->dropColumn('bg_image_id');
        });
    }
}
