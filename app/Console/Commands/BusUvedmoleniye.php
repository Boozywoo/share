<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Channels\SmsChannel;
use App\Models\Bus;

class BusUvedmoleniye extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'busto:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $companies = Bus::with('company')->get();
        foreach ($companies->groupBy('company_id') as $buses) {
            $message = '';
            foreach ($buses as $bus) {
                $date_bv = date("Y-m-d",strtotime("+".$bus->day_before_revision." days"));
                $date_bi = date("Y-m-d",strtotime("+".$bus->day_before_insurance." days"));
                if($bus->insurance_day > $date_bi) {
                    $message .= "У ".$bus->name." заканчивается страховка ".$bus->insurance_day;
                }
                if($bus->revision_day > $date_bv) {
                    $message .= "У ".$bus->name." заканчивается лицензия ".$bus->revision_day;
                }
            }
            $sms = new SmsChannel();
            $sms->send(null, null, $bus->universal_field, $bus->company->phone);
        }
    }
}
