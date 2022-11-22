<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderIdToSmsLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @retrn void
     */
    public function up()
    {
        Schema::table('sms_logs', function (Blueprint $table) {
            $table->dateTime('send_datetime')->after('message')->nullable();
            $table->dateTime('confirm_datetime')->after('message')->nullable();
            $table->string('error')->after('message')->nullable();
            $table->string('status')->after('message')->nullable();
            $table->string('message_id')->after('user_send_id')->nullable();
            $table->integer('order_id')->after('user_send_id')->unsigned()->nullable()->index();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sms_logs', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn(['order_id', 'error', 'status', 'message_id','send_datetime', 'confirm_datetime']);
        });
    }
}