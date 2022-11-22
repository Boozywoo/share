<?php

namespace App\Console;

use App\Console\Commands\BackupDatabase;
use App\Console\Commands\BusUvedmoleniye;
use App\Console\Commands\CheckBusUniversalTime;
use App\Console\Commands\CheckDriver;
use App\Console\Commands\ClearStations;
use App\Console\Commands\CompleteTours;
use App\Console\Commands\DisableOrderNoPay;
use App\Console\Commands\InsertClients;
use App\Console\Commands\MinskTrans\MinskTransSyncTours;
use App\Console\Commands\RemoveHistory;
use App\Console\Commands\RemoveOrderNoCompleted;
use App\Console\Commands\RepairBus;
use App\Console\Commands\TempCommand;
use App\Console\Commands\TestCommand;
use App\Console\Commands\TestTelegram;
use App\Console\Commands\UserPayMonth;
use App\Console\Commands\RepeatSchedule;
use App\Jobs\Tour\CompletedTourJob;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\CronRun;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        RepairBus::class,
        RepeatSchedule::class,
        RemoveOrderNoCompleted::class,
        CheckDriver::class,
        UserPayMonth::class,
        ClearStations::class,
        InsertClients::class,
        TestCommand::class,
        TempCommand::class,
        TestTelegram::class,
        MinskTransSyncTours::class,
        CompleteTours::class,
        BackupDatabase::class,
        DisableOrderNoPay::class,
        RemoveHistory::class,
        CronRun::class,
        CheckBusUniversalTime::class,
        BusUvedmoleniye::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('repair:bus')->dailyAt('00:00');
        $schedule->command('repeat:schedule')->dailyAt('00:10');
        $schedule->command('remove:order-no-completed')->everyFiveMinutes();
        $schedule->command('disable:order-no-pay')->everyFiveMinutes();
        $schedule->command('driver:check')->dailyAt('12:00');
        $schedule->command('pay:month')->monthly();
        $schedule->command('bus:check')->everyFiveMinutes();
        $schedule->command('busto:check')->dailyAt('12:00');
        $schedule->command('clear:stations')->daily();
        $schedule->command('clear:history')->daily();
        $schedule->command('complete:tours')->dailyAt('08:00')->when(function () {
            $setting = Setting::first();
            return ( $setting && $setting->complete_tours );
        });
        if (env('MINSK_TRANS')) {
            //  $schedule->command('minsk_trans:sync_tours')->everyFiveMinutes();
        }
        $schedule->command('cron:run')->everyMinute();
    }


    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
