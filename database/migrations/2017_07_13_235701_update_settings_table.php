<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('phone_one');
            $table->string('phone_two');
            $table->string('phone_tree');
            $table->string('company_name');
            $table->string('text_footer');
            $table->string('address');
            $table->string('copyright');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'phone_one', 'phone_two', 'phone_tree',
                'company_name', 'text_footer', 'address', 'copyright',
            ]);
        });
    }
}
