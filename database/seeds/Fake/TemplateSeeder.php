<?php

use App\Models\Template;
use App\Models\TemplatePlace;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    public function run()
    {
        Template::query()->delete();

        $templateFake = [
            0 => [
                'name' => 'Шаблон 15 мест 1-2-3-3-3-3',
                'ranks' => 4,
                'columns' => 6,
                'count_places' => 15,
                'places' => [
                    'D0', null, 'D2', 'D3', 'D4', 'D5',
                    null, null, null, null, null, null,
                    null, 'B1', 'B2', 'B3', 'B4', 'B5',
                    'DR', 'A1', 'A2', 'A3', 'A4', 'A5',
                ]
            ],
            1 => [
                'name' => 'Шаблон 15 мест 0-3-3-3-3-3',
                'ranks' => 4,
                'columns' => 6,
                'count_places' => 15,
                'places' => [
                    null, 'D1', 'D2', 'D3', 'D4', 'D5',
                    null, null, null, null, null, null,
                    null, 'B1', 'B2', 'B3', 'B4', 'B5',
                    'DR', 'A1', 'A2', 'A3', 'A4', 'A5',
                ]
            ],
            2 => [
                'name' => 'Шаблон 15 мест 0-2-3-3-3-4',
                'ranks' => 4,
                'columns' => 6,
                'count_places' => 15,
                'places' => [
                    null, null, 'D2', 'D3', 'D4', 'D5',
                    null, null, null, null, null, 'C5',
                    null, 'B1', 'B2', 'B3', 'B4', 'B5',
                    'DR', 'A1', 'A2', 'A3', 'A4', 'A5',
                ]
            ],
            3 => [
                'name' => 'Шаблон 15 мест 2-2-2-3-3-3',
                'ranks' => 4,
                'columns' => 6,
                'count_places' => 15,
                'places' => [
                    'D0', null, null, 'D3', 'D4', 'D5',
                    'C0', null, null, null, null, null,
                    null, 'B1', 'B2', 'B3', 'B4', 'B5',
                    'DR', 'A1', 'A2', 'A3', 'A4', 'A5',
                ]
            ],
            4 => [
                'name' => '	Шаблон 19 мест 1-2-3-3-3-3-4',
                'ranks' => 4,
                'columns' => 7,
                'count_places' => 19,
                'places' => [
                    'D0', null, 'D2', 'D3', 'D4', 'D5', 'D6',
                    null, null, null, null, null, null, 'C6',
                    null, 'B1', 'B2', 'B3', 'B4', 'B5', 'B6',
                    'DR', 'A1', 'A2', 'A3', 'A4', 'A5', 'A6',
                ]
            ],
            5 => [
                'name' => 'Шаблон 19 мест: 0-3-3-3-3-3-4',
                'ranks' => 4,
                'columns' => 7,
                'count_places' => 19,
                'places' => [
                    null, 'D1', 'D2', 'D3', 'D4', 'D5', 'D6',
                    null, null, null, null, null, null, 'C6',
                    null, 'B1', 'B2', 'B3', 'B4', 'B5', 'B6',
                    'DR', 'A1', 'A2', 'A3', 'A4', 'A5', 'A6',
                ]
            ],
        ];

        foreach ($templateFake as $item) {
            $template = Template::create([
                'name' => $item['name'],
                'ranks' => $item['ranks'],
                'columns' => $item['columns'],
                'count_places' => $item['count_places'],
            ]);
            $templatePlaces = [];
            foreach ($item['places'] as $place) {
                switch ($place) {
                    case null:
                        $type = TemplatePlace::TYPE_DELETE;
                        $number = null;
                        break;
                    case 'DR':
                        $type = TemplatePlace::TYPE_DRIVER;
                        $number = null;
                        break;
                    default:
                        $type = TemplatePlace::TYPE_NUMBER;
                        $number = $place;
                        break;
                }
                $templatePlaces[] = new TemplatePlace([
                    'type' => $type,
                    'number' => $number
                ]);
                $template->templatePlaces()->saveMany($templatePlaces);
            }
        }
    }
}
