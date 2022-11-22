<?php

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\User;

class MinskTransClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = Client::firstOrCreate(['first_name' => 'Системный пользователь'],
            [
                'first_name' => 'Системный пользователь',
                'email' => 'mosgortrans@tm.by',
                'status' => Client::STATUS_SYSTEM,
            ]
        );
        $user = User::firstOrCreate(
            [
                'client_id' => $client->id
            ],
            [
                'client_id' => $client->id,
            ]
        );
    }
}
