<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UserPayMonth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pay:month';

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
        $user = new User();
        $operators = $user->with('companies', 'routes')->whereHas('roles', function ($q) {
            $q->whereIn('slug', ['operator', 'agent']);
        })->get();

        foreach ($operators as $operator) {
            foreach ($operator->companies as $company)
                if ($company->pivot->pay_month_fix)
                    \DB::table('user_pay_month')->insert(
                        [
                            'user_id' => $operator->id,
                            'month' =>  date('n') == 1 ? 12 : date('n') - 1,
                            'year' => date('n') == 1 ? date('Y') -1 :  date('Y'),
                            'sum' => $company->pivot->pay_month_fix,
                            'company_id' => $company->id,
                        ]
                    );
            foreach ($operator->routes as $route)
                if ($route->pivot->pay_month_fix)
                    \DB::table('user_pay_month')->insert(
                        [
                            'user_id' => $operator->id,
                            'month' => date('n') == 1 ? 12 : date('n') - 1,
                            'year' => date('n') == 1 ? date('Y') -1 :  date('Y'),
                            'sum' => $route->pivot->pay_month_fix,
                            'route_id' => $route->id,
                        ]
                    );
        }
    }
}
