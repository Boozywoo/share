<?php

namespace App\Console\Commands;

use App\Channels\SmsChannel;
use App\Models\Client;
use App\Models\Code;
use App\Models\Route;
use App\Models\Tour;
use App\Notifications\Client\SendCodeNotification;
use App\Notifications\Rent\ActiveRentNotification;
use App\Services\Client\StoreClientService;
use App\Services\Prettifier;
use App\Services\Rent\CalculateDistance;
use App\Services\Rent\CalculateDuration;
use Illuminate\Console\Command;

class TempCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:temp';

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
        $client = Client::find(2);
        $tour = Tour::find(1);
        $tour->rent->methodist->notify(new ActiveRentNotification($tour));
    }
}
