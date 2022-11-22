<?php

namespace App\Console\Commands;

use App\Jobs\Bus\BusImportJob;
use App\Jobs\Driver\DriverImportJob;
use App\Jobs\User\UserImportJob;
use App\Models\City;
use App\Models\Client;
use App\Models\Company;
use App\Models\Order;
use App\Models\Rent;
use App\Models\Route;
use App\Models\Route as RouteModel;
use App\Models\Station;
use App\Models\Street;
use App\Models\Template;
use App\Models\Tour;
use App\Notifications\Order\DisableOrderNotification;
use App\Services\Code\SendCodeService;
use App\Services\Log\TelegramLog;
use App\Services\Prettifier;
use App\Services\Rent\CheckFullData;
use Carbon\Carbon;
use Illuminate\Console\Command;
use mttzzz\laravelTelegramLog\Telegram;

class testCommand extends Command
{
    protected $signature = 'test';
    protected $description = 'Тестовая команда';

    public $file;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        /*Order::whereHas('tour', function ($q) {
            $q->where('date_start', '<', Carbon::now()->subDays(7)->startOfDay());
        })->delete();
        Tour::where('date_start', '<', Carbon::now()->subDays(7)->startOfDay())
            ->delete();*/
    }
}