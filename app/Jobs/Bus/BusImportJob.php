<?php

namespace App\Jobs\Bus;

use App\Models\Bus;
use App\Models\Company;
use App\Models\Template;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BusImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $buses;

    public function __construct($buses)
    {
        $this->buses = $buses;
    }

    public function handle()
    {
        foreach ($this->buses as $bus) {
            $company = Company::where('name', $bus['kompaniya'])->first();
            $company = $company ? $company : Company::first();
            $template = Template::where('name', $bus['nazvanie_shablona'])->first();
            $template = $template ? $template : Template::first();
            if ($template && $company) {
                $busData = [
                    'name' => $bus['nazvanie_avtobusa'],
                    'name_tr' => $bus['nazvanie_dlya_sms'],
                    'number' => $bus['gos_nomer'],
                    'template_id' => $template->id,
                    'company_id' => $company->id,
                    'places' => $template->count_places,
                ];
                Bus::firstOrCreate(['number' => $bus['gos_nomer']], $busData);
            }
        }
    }
}
