<?php

use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currency::firstOrCreate([
            'alfa' => 'RUB',
            'number' => 643,
        ]);
        Currency::firstOrCreate([
            'alfa' => 'BYN',
            'number' => 933,
        ]);
        Currency::firstOrCreate([
            'alfa' => 'UAH',
            'number' => 980,
        ]);
        Currency::firstOrCreate([
            'alfa' => 'KZT',
            'number' => 398,
        ]);
        Currency::firstOrCreate([
            'alfa' => 'EUR',
            'number' => 978,
        ]);
        Currency::firstOrCreate([
            'alfa' => 'USD',
            'number' => 840,
        ]);


        $currencies = Currency::all();
        foreach ($currencies as $currency){
            $currency->name = $currency->alfa;
            $currency->save();
        }
    }
}
