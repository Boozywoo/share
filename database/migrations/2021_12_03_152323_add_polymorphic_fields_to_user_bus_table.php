<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPolymorphicFieldsToUserBusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bus', function (Blueprint $table) {
            $table->unsignedInteger('imageable_id');
            $table->string('imageable_type');
            $table->dropForeign('user_bus_user_id_foreign');
            $table->dropColumn(['status', 'condition', 'diagnostic_card_id', 'type', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_bus', function (Blueprint $table) {
            $table->dropColumn(['imageable_id', 'imageable_type']);
            $table->unsignedInteger('user_id');
            $table->tinyInteger('status')->nullable();
            $table->unsignedInteger('condition')->nullable();
            $table->unsignedInteger('diagnostic_card_id');
            $table->string('type')->default('review');
        });
    }
}
