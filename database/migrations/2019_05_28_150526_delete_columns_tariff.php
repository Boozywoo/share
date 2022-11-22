<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteColumnsTariff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tariffs', function (Blueprint $table) {
            $table->dropForeign(['agreement_id']);
            $table->dropColumn(['agreement_id', 'min', 'max', 'cost']);
            $table->string('name')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tariffs', function (Blueprint $table) {
            $table->integer('agreement_id')->unsigned()->index()->nullable();
            $table->foreign('agreement_id')->references('id')->on('agreements')->onDelete('cascade');
            $table->integer('min')->nullable();
            $table->integer('max')->nullable();
            $table->float('cost')->nullable();
            $table->dropColumn(['name']);
        });
    }
}
