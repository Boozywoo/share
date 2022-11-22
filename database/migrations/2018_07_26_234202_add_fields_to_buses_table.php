<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToBusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->date('universal_day')->after('comment')->nullable();
            $table->text('universal_field')->after('comment')->nullable();
            $table->decimal('garage_latitude', 10, 6)->after('comment')->nullable();
            $table->decimal('garage_longitude', 10, 6)->after('comment')->nullable();
            $table->string('type')->after('comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */

    public function down()
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->dropColumn(['universal_day', 'universal_field', 'type', 'garage_latitude', 'garage_longitude']);
        });
    }
}
