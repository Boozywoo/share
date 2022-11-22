<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Real
        $this->call(RolesSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(PageSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(ConfigSeeder::class);
        $this->call(SmsConfigSeeder::class);

        $this->call(updatepermissiontable::class);
        $this->call(DriverAppSettingsSeeder::class);
        $this->call(SiteSettingsSeeder::class);
        $this->call(PermissionInterfaceSettingsSeeder::class);
        $this->call(PermissionStatisticSeeder::class);

        $this->call(InterfaceSettingsTableSeeder::class);

        $this->call(AmenitySeeder::class);

        //Fake
        //$this->call(TemplateSeeder::class);
        //$this->call(CompanySeeder::class);
        //$this->call(RouteSeeder::class);
        //$this->call(ScheduleSeeder::class);
        //$this->call(ClientSeeder::class);
        //$this->call(OrderSeeder::class);

        if (env('MINSK_TRANS')) {
            $this->call(MinskTransIntegrationSeeder::class);
            $this->call(MinskTransRouteSeed::class);
            $this->call(MinskTransTemplateSeeder::class);
            $this->call(MinskTransClientSeeder::class);
            $this->call(MinskTransCompanySeeder::class);
        }
    }
}
