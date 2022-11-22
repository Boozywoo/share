<?php

namespace App\Jobs\Tour;

use App\Models\Client;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CompletedTourJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tour;

    public function __construct(Tour $tour)
    {
        $this->tour = $tour;
    }

    public function handle()
    {
        $orders = $this->tour->ordersReady()->get();
        //$orders = $this->tour->ordersReady()->whereType(Order::TYPE_WAITING)->get();
        foreach ($orders as $order) {
            foreach ($order->orderPlaces as $orderPlace) {
                if ($orderPlace->appearance === NULL) {
                    $orderPlace->appearance = true;
                    $orderPlace->save();
                }
            }

            $countPlaces = $order->orderPlaces->count();
            $countPlacesAppearance = $order->orderPlaces->where('appearance', 1)->count();
            $countPlacesNoAppearance = $countPlaces - $countPlacesAppearance;

            $order->count_places_appearance = $countPlacesAppearance;
            $order->count_places_no_appearance = $countPlacesNoAppearance;
            $order->type = Order::TYPE_COMPLETED;
            $order->update();

            $settings = Setting::all()->first();

            $client = $order->client;

            if($settings->is_client_statistic == 0) {
                if ($client instanceof Client) {
                    $client->order_success += isset($countPlacesAppearance) ? $countPlacesAppearance : 0;
                    $client->order_error += isset($countPlacesNoAppearance) ? $countPlacesNoAppearance : 0;
                    $client->update();
                }
            }
            
            $this->setPayOrder($order);
        }
    }


    public function setPayOrder(Order $order)
    {
        if (($operator = $order->operator) && ($order->operator->hasRole('operator') || $order->operator->isAgent || $order->operator->isMediator)) {
            if ($order->tour->bus && $company = $operator->companies->where('id', $order->tour->bus->company->id)->first())
            {
                $company_fix = $company->pivot->pay_order_fix ?: 0;
                $company_percent = $company->pivot->pay_order_percent ? $company->pivot->pay_order_percent * ($order->price/100) : 0;
                if ($sum = ($company_fix + $company_percent))
                    \DB::table('user_pay_order')->insert(
                        [
                            'user_id' => $operator->id,
                            'order_id' => $order->id,
                            'sum' => $sum,
                            'percent' => $company->pivot->pay_order_percent,
                            'fix' => $company_fix,
                            'company_id' => $company->id,
                        ]
                    );
            }

            if ($route = $operator->routes->where('id', $order->tour->route->id)->first())
            {
                $route_fix = $route->pivot->pay_order_fix ?: 0;
                $route_percent = $route->pivot->pay_order_percent ? $route->pivot->pay_order_percent * ($order->price/100) : 0;
                if ($sum = ($route_fix + $route_percent))
                    \DB::table('user_pay_order')->insert(
                        [
                            'user_id' => $operator->id,
                            'order_id' => $order->id,
                            'sum' => $sum,
                            'percent' => $route->pivot->pay_order_percent,
                            'fix' => $route_fix,
                            'route_id' => $route->id,
                        ]
                    );
            }
        }
    }
}
