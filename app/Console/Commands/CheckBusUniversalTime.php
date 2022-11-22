<?php

namespace App\Console\Commands;

use App\Channels\SmsChannel;
use Illuminate\Console\Command;
use App\Models\Bus;

class CheckBusUniversalTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bus:check';

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
        //
        $buses = Bus::with('company')
            ->whereDate('universal_day', "<", date("Y-m-d H:i:s", strtotime('+5 minutes')))
            //->whereDate('universal_day',">",date("Y-m-d H:i:s"))
            ->get();

        foreach ($buses->groupBy('company_id') as $bus) {
            foreach ($buses as $busd) {
                dump($busd->company->phone);
                $sms = new SmsChannel();
                $sms->send(null, null, $busd->universal_field, $busd->company->phone);
            }

        }

    }
}