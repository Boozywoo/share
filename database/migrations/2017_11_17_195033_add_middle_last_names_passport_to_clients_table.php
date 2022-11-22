<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMiddleLastNamesPassportToClientsTable extends Migration
{

    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->char('passport', 50)->after('first_name')->nullable()->default(NULL);
            $table->char('last_name', 255)->after('first_name')->nullable()->default(NULL);
            $table->char('middle_name', 255)->after('first_name')->nullable()->default(NULL);
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('middle_name');
            $table->dropColumn('last_name');
            $table->dropColumn('passport');
        });
    }
}
