<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTypeColumnsInterfaceSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('interface_settings', function (Blueprint $table) {
            $table->string('fontColor')->change();
            $table->string('bgColor')->change();
            $table->string('bgHoverColor')->change();
            $table->string('wrapperColor')->change();
            $table->string('menuFocusStyle')->change();
            $table->string('menuHoverStyle')->change();
            $table->string('phoneBtnBgStyle')->change();
            $table->string('bgWarning')->change();
            $table->string('confirmPopupBgColor')->change();
            $table->string('confirmPopupFontColor')->change();
            $table->string('textLink')->change();
            $table->string('textLinkHover')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('interface_settings', function (Blueprint $table) {
            $table->text('fontColor')->change();
            $table->text('bgColor')->change();
            $table->text('bgHoverColor')->change();
            $table->text('wrapperColor')->change();
            $table->text('menuFocusStyle')->change();
            $table->text('menuHoverStyle')->change();
            $table->text('phoneBtnBgStyle')->change();
            $table->text('bgWarning')->change();
            $table->text('confirmPopupBgColor')->change();
            $table->text('confirmPopupFontColor')->change();
            $table->text('textLink')->change();
            $table->text('textLinkHover')->change();
        });
    }
}
