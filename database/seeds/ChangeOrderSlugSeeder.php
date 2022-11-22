<?php

use Illuminate\Database\Seeder;
use App\Models\Order;

class ChangeOrderSlugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = Order::all();

        foreach ($orders as $order) {
            $order->slug = $order->client_id;
            $order->save();
        }
    }
}
