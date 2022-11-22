<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountForMobileRoutes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->float('discount_mobile')->default(0)->after('discount_child_type');
        });
        Schema::table('routes', function (Blueprint $table) {
            $table->boolean('discount_mobile_type')->default(false)->after('discount_mobile')
                ->comment('Тип скидки при бронировании на сайте, false - в абсолютном выражении, true - в процентном выражении');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->dropColumn(['discount_mobile']);
        });
        Schema::table('routes', function (Blueprint $table) {
            $table->dropColumn('discount_mobile_type');
        });
    }
}
