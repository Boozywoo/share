<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInterfaceSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('interface_settings', function (Blueprint $table) {
            $table->text('fontColor')->after('theme_name');
            $table->text('bgColor')->after('fontColor');
            $table->text('bgHoverColor')->after('bgColor');
            $table->text('wrapperColor')->after('bgHoverColor');
            $table->text('menuFocusStyle')->after('wrapperColor');
            $table->text('menuHoverStyle')->after('menuFocusStyle');
            $table->text('phoneBtnBgStyle')->after('menuHoverStyle');
            $table->text('bgWarning')->after('phoneBtnBgStyle');
            $table->text('confirmPopupBgColor')->after('bgWarning');
            $table->text('confirmPopupFontColor')->after('confirmPopupBgColor');
            $table->text('textLink')->after('confirmPopupFontColor');
            $table->text('textLinkHover')->after('textLink');
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
            $table->dropColumn(
                [
                    'fontColor', 'bgColor', 'bgHoverColor', 'wrapperColor', 'menuFocusStyle', 
                    'menuHoverStyle', 'phoneBtnBgStyle', 'bgWarning', 'confirmPopupBgColor',
                    'confirmPopupFontColor', 'textLink', 'textLinkHover',
                ]
            );
        });
    }
}
