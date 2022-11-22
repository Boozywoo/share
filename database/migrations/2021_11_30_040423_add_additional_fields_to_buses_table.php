<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalFieldsToBusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->string('vin')->nullable();
            $table->string('driver_category')->nullable();
            $table->unsignedInteger('year')->nullable();
            $table->string('color')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('vehicle_passport')->nullable();
            $table->date('vehicle_passport_date')->nullable();
            $table->string('registration_certificate')->nullable();
            $table->date('registration_certificate_date')->nullable();
            $table->string('insurance_policy')->nullable();
            $table->unsignedInteger('inventory_number')->nullable();
            $table->string('engine_model')->nullable();
            $table->string('engine_number')->nullable();
            $table->string('engine_power')->nullable();
            $table->unsignedInteger('weight_allowed')->nullable();
            $table->unsignedInteger('weight_empty')->nullable();
            $table->double('balance_price')->nullable();
            $table->double('residual_price')->nullable();
            $table->double('transport_tax')->nullable();
            $table->double('property_tax')->nullable();
            $table->string('chassis_number')->nullable();
            $table->string('body_number')->nullable();
            $table->unsignedInteger('diagnostic_card_number')->nullable();
            $table->date('diagnostic_card_date')->nullable();
            $table->unsignedInteger('owner_id')->nullable();
            $table->unsignedInteger('customer_id')->nullable();
            $table->date('commissioning_date')->nullable();
            $table->string('tires')->nullable();
            $table->string('structure_department')->nullable();
            $table->string('owner_legally')->nullable();
            $table->string('customer_company')->nullable();
            $table->string('customer_department')->nullable();
            $table->string('customer_director')->nullable();

            $table->renameColumn('odometer', 'operating_mileage');
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
            $table->renameColumn('operating_mileage', 'odometer');

            $table->dropColumn(['vin', 'driver_category', 'year', 'color', 'manufacturer', 'vehicle_passport', 'vehicle_passport_date',
                'registration_certificate', 'registration_certificate_date', 'insurance_policy', 'inventory_number',
                'engine_model', 'engine_number', 'engine_power', 'weight_allowed', 'weight_empty', 'balance_price',
                'residual_price', 'transport_tax', 'property_tax', 'chassis_number', 'body_number', 'diagnostic_card_number', 'diagnostic_card_date',
                'owner_id', 'customer_id', 'commissioning_date', 'tires', 'structure_department', 'owner_legally', 'customer_company',
                'customer_department', 'customer_director'
            ]);
        });
    }
}
