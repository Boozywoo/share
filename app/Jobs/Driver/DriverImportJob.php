<?php

namespace App\Jobs\Driver;

use App\Models\Company;
use App\Models\Driver;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DriverImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $drivers;

    public function __construct($drivers)
    {
        $this->drivers = $drivers;
    }

    public function handle()
    {
        foreach ($this->drivers as $driver) {
            $company = Company::where('name', $driver['kompaniya'])->first();
            $company = $company ? $company : Company::first();
            $phone = preg_replace('/[^0-9.]+/', '', $driver['telefon_dlya_prilozheniya']);
            $workPhone = preg_replace('/[^0-9.]+/', '', $driver['rabochiy_telefon_dlya_klientov_v_sms']);

            if ($phone && $driver['fio']) {
                $driverData = [
                    'full_name' => $driver['fio'],
                    'phone' => $phone,
                    'work_phone' => $workPhone,
                    'password' => empty($driver['parol']) ? 'password' : $driver['parol'],
                    'company_id' => $company->id
                ];
                Driver::firstOrCreate(['phone' => $phone], $driverData);
            }
        }
    }
}
