<?php

use App\Models\BusType;
use App\Models\CarColor;
use Illuminate\Database\Seeder;

class CarSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $colors = [
          [
              'slug' => 'white',
              'name' => 'Белый'
          ],
          [
              'slug' => 'blue',
              'name' => 'Синий'
          ],
          [
              'slug' => 'green',
              'name' => 'Зеленый'
          ],
          [
              'slug' => 'beige',
              'name' => 'Бежевый'
          ],
          [
              'slug' => 'red',
              'name' => 'Красный'
          ],
          [
              'slug' => 'violet',
              'name' => 'Фиолетоовый'
          ],
          [
              'slug' => 'orange',
              'name' => 'Оранжевый'
          ],
          [
              'slug' => 'black',
              'name' => 'Черный'
          ],
          [
              'slug' => 'yellow',
              'name' => 'Желтый'
          ],
          [
              'slug' => 'brown',
              'name' => 'Коричневый'
          ],
          [
              'slug' => 'grey',
              'name' => 'Серый'
          ],
          [
              'slug' => 'silver',
              'name' => 'Серебристый'
          ],
          [
              'slug' => 'red-orange',
              'name' => 'Красно-оранжевый'
          ],
          [
              'slug' => 'navy-blue',
              'name' => 'Темно-синий'
          ],
          [
              'slug' => 'silver-metallic',
              'name' => 'Серебристый металлик'
          ],
          [
              'slug' => 'black-metallic',
              'name' => 'Черный металлик'
          ],
        ];

        foreach ($colors as $color){
            CarColor::firstOrCreate($color);
        }

        $types = [
            [
                'name' => 'автобус'
            ],
            [
                'name' => 'грузовой-бортовой'
            ],
            [
                'name' => 'грузовой фургон'
            ],
            [
                'name' => 'легковой'
            ],
            [
                'name' => 'пассажирский фургон'
            ],
            [
                'name' => 'минипогрузчик'
            ],
        ];

        foreach ($types as $type) {
            BusType::firstOrCreate($type);
        }
    }
}
