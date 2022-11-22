<?php

namespace App\Console\Commands;

use App\Channels\SmsChannel;
use App\Models\Driver;
use Illuminate\Console\Command;

class CheckDriver extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'driver:check';

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
        $date = date("Y-m-d", strtotime("+15 days"));
//        $companies = Driver::with('company')
//            ->where('med_day', '<', $date)
//            ->orWhere('driver_license', '<', $date)
//            ->get();
        $companies = Driver::with('company')->get();

        foreach ($companies->groupBy('company_id') as $drivers) {
            $message = '';
            foreach ($drivers as $driver) {

                $date_med_day = date("Y-m-d",strtotime($driver->med_day ."-".$driver->day_before_med_day." days"));
                $date_driver_license = date("Y-m-d",strtotime($driver->driver_license."-".$driver->day_before_driver_license." days"));
                $day_ended_visa = date("Y-m-d", strtotime($driver->end_visa."-".$driver->day_before_end_visa." days"));
                if ($date_med_day === date ("Y-m-d") && !empty($driver->med_day)) {

                    $message .= $driver->full_name . ' заканчивается мед.страховка ' . date("d-m-Y", strtotime($driver->med_day)) . "\n";
                }
                if ($date_driver_license === date ("Y-m-d") && !empty($driver->driver_license)) {
                    $message .= $driver->full_name . ' заканчивается водительское удостоверение ' . date("d-m-Y", strtotime($driver->driver_license)) . "\n";

                }
                if ($day_ended_visa === date ("Y-m-d") && !empty($driver->end_visa)) {
                    $message .= $driver->full_name . ' заканчивается виза ' . date("d-m-Y", strtotime($driver->end_visa)) . "\n";
                }


            }
            $sms = new SmsChannel();
            $sms->send(null, null, $message, $driver->company->phone);
            if($message!=null) {
            // dump($message);
            }
        }
    }


}
