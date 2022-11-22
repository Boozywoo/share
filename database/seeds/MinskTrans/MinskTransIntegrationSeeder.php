<?php

use Illuminate\Database\Seeder;
use App\Models\Integration;

class MinskTransIntegrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Integration::updateOrCreate([
            'slug' => 'mosgortrans'
        ], [
            'name' => 'МосГорТранс',
            'slug' => 'mosgortrans',
            'active' => true,
        ]);
    }
}
