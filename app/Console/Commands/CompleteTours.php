<?php

namespace App\Console\Commands;

use App\Jobs\Tour\CompletedTourJob;
use App\Models\Tour;
use Illuminate\Console\Command;

class CompleteTours extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'complete:tours';

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
        $tours = Tour::where('date_start', '<', date('Y-m-d'))
            ->where('status', '<>', Tour::STATUS_COMPLETED)
            ->get();

        foreach ($tours as $tour) {
            dispatch(new CompletedTourJob($tour));
            $tour->status = Tour::STATUS_COMPLETED;
            $tour->save();
        }
    }
}
