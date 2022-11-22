<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Setting;
use App\Models\Tour;

class RemoveHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:history';

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

    const DEFAULT_DAYS = 1000;


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
        $settings = Setting::first();
        $days = ($settings->history_days) ? ($settings->history_days*1):(self::DEFAULT_DAYS);

        Tour::where('date_start','<', \Carbon\Carbon::now()->subDays($days))->delete();
    }
}
