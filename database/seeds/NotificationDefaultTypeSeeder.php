<?php

use App\Models\NotificationType;
use Illuminate\Database\Seeder;

class NotificationDefaultTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'name' => 'default',
            'slug' => 'default',
            'approved' => 0,
            'read' => 0,
            'denied' => 0,
            'view' => 1,
        ];

        NotificationType::firstOrCreate($data);
    }
}
