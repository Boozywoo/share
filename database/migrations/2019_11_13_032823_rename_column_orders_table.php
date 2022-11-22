<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameColumnOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('human_number', 'slug');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('slug', '20')->change();
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
            $table->renameColumn('slug', 'human_number');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('human_number', '15')->change();
        });
    }
}
