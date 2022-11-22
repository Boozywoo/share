<?php

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Bus;
use App\Models\Template;
use App\Models\Driver;

class MinskTransCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::firstOrCreate(['name' => 'МинскТранс'],[
            'name' => 'МинскТранс',
            'responsible' => 'МинскТранс',
            'position' => 'Главный механик',
            'phone' => '375000000000',
            'phone_sub' => '375000000000',
            'status' => Company::STATUS_ACTIVE,
            'reputation' => Company::REPUTATION_NEW,
            'requisites' => 'реквизиты',
            'phone_resp' => '375000000000',
        ]);

        $bus = Bus::firstOrCreate(['name' => 'Системный (44 места)'],[
            'name_tr' => 'AvtoBus 44 mest',
            'number' => '7777-MM7',
            'places' => 44,
            'status' => Bus::STATUS_SYSTEM,
            'company_id' => $company->id,
            'template_id' => Template::where('name', 'МинскТранс (44 места)')->first()->id,
        ]);

        $driver = Driver::firstOrCreate(['full_name' => 'Системный водитель'],[
            'phone' => '375000000000',
            'password' => 'AvtoBus 44 mest',
            'status' => Driver::STATUS_SYSTEM,
            'reputation' => Driver::REPUTATION_NEW,
            'company_id' => $company->id,
        ]);
    }
}
