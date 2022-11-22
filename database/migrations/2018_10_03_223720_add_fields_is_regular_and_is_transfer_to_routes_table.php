<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsIsRegularAndIsTransferToRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        {
            Schema::table('routes', function (Blueprint $table) {
                $table->boolean('is_regular')->after('is_taxi');
                $table->boolean('is_transfer')->after('is_regular');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn(['is_regular', 'is_transfer']);
        });
    }
}
