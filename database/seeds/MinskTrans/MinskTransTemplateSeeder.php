<?php


use App\Models\Template;
use App\Models\TemplatePlace;
use Illuminate\Database\Seeder;

class MinskTransTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'МинскТранс (44 места)';
        if (!Template::where('name', $name)->first()) {
            $templates = [
                0 => [
                    'name' => $name,
                    'ranks' => 5,
                    'columns' => 13,
                    'count_places' => 44,
                    'places' => [
                        null, null, '4', '8', '12', '16', '20', '24', '28', '32', '36', '40', '44',
                        null, null, '3', '7', '11', '15', '19', '23', '27', '31', '35', '39', '43',
                        null, null, null, null, null, null, null, null, null, null, null, null, null,
                        null, null, '2', '6', '10', '14', '18', '22', '26', '30', '34', '38', '42',
                        'DR', null, '1', '5', '9', '13', '17', '21', '25', '29', '33', '37', '41',
                    ]
                ]
            ];

            foreach ($templates as $item) {
                $template = Template::firstOrCreate(['name' => $name],
                    [
                        'name' => $name,
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
}
