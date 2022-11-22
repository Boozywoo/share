<?php

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run()
    {
        Client::where('id', '!=', 'a')->delete();

        $this->faker = Faker\Factory::create('ru_RU');

        factory(Client::class, 100)->create();

    }
}
