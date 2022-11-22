<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAddServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('add_services', function (Blueprint $table) {
            $table->dropColumn(['percent', 'is_percent']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('add_services', function (Blueprint $table) {
            $table->float('percent')->nullable();
            $table->boolean('is_percent')->default(false);
        });
    }
}
