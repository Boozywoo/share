<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldDepartmentsNotificationWishesTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wishes_types', function (Blueprint $table) {
            $table->json('departments_notification')->after('notifi_supervisor')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wishes_types', function (Blueprint $table) {
            $table->dropColumn('departments_notification');
        });
    }
}
