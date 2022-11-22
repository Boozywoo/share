<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreatedModifiedCanceledUsersOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('created_user_id')->nullable()->unsigned()->index();
            $table->foreign('created_user_id')->references('id')->on('users')->onDelete('set null');
            $table->integer('modified_user_id')->nullable()->unsigned()->index();
            $table->foreign('modified_user_id')->references('id')->on('users')->onDelete('set null');
            $table->integer('canceled_user_id')->nullable()->unsigned()->index();
            $table->foreign('canceled_user_id')->references('id')->on('users')->onDelete('set null');
        });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['canceled_user_id', 'modified_user_id', 'created_user_id']);
            $table->dropColumn(['canceled_user_id', 'modified_user_id', 'created_user_id']);
        });
    }
}
