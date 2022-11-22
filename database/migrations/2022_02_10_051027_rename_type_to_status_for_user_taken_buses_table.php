<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTypeToStatusForUserTakenBusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_taken_buses', function (Blueprint $table) {
            $table->renameColumn('type', 'status');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_taken_buses', function (Blueprint $table) {
            $table->renameColumn('status', 'type');
            $table->dropColumn(['started_at', 'ended_at']);
        });
    }
}
