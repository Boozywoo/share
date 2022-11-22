<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeDriver extends Migration
{
    public function up()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->boolean('is_admin_driver')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn(['is_admin']);
        });
    }
}
