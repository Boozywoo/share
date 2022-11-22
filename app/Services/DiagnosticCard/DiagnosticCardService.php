<?php

namespace App\Services\DiagnosticCard;

use App\Models\DiagnosticCard;
use App\Models\ReviewAct;
use App\Models\ReviewActItem;
use App\Models\ReviewActTemplateItem;
use Websecret\Panel\Http\Controllers\UploadController;

class DiagnosticCardService
{
    /*
    * Создание новой диагностической карты в БД
    */
    public function store($data)
    {
        $diagnostic_card = DiagnosticCard::create([
            'bus_id' => $data['bus_id'],
            'diagnostic_card_template_id' => $data['diagnostic_card_template_id'],
            'km' => $data['km'],
            'fuel' => $data['fuel'],

            // значения по умолчанию
            'date_exec' => date("d.m.Y"),
            'status' => DiagnosticCard::STATUS_OK,
            'sap_number' => 0,
            'reg_number' => 0,
            'master_id' => 0,
            'contractor_id' => 0,
        ]);

        // Запись изменяемых параметров автомобиля в отдельную таблицу
        $diagnostic_card->bus_variable()->create([
            'km' => $data['km'],
            'fuel' => $data['fuel'],
            'bus_id' => $data['bus_id']
        ]);

        // список шаблонов актов осмотра, входящих в шаблон
        // создаваемой диагностической карты
        $review_act_templates = $diagnostic_card->template->items()->get();

        // массив, задающий соответствие между шаблонами актов осмотра
        // и самими актами осмотра, из которых состоит данная 
        // диагностическая карта. Формат массива:
        // ['id шаблона акта осмотра' => 'акт осмотра']
        $review_acts = [];

        // заполнение массива $review_acts, создание соответствующих
        // ему актов осмотра и запись созданных актов
        // осмотра в БД
        foreach($review_act_templates as $template) {
            $act = ReviewAct::create([
                'bus_id' => $data['bus_id'],
                'review_act_template_id' => $template->id,
                'diagnostic_card_id' => $diagnostic_card->id,
            ]);
            $review_acts[$template->id] = $act;
        }

        // перебор полученных полей всех актов осмотра из карты 
        foreach ($data as $key => $value) {
            $key_explode = explode('_', $key);

            if ($key_explode[0] == 'body') {
                // элемент шаблона акта, соответствующий создаваемому элементу акта 
                $review_act_template_item = ReviewActTemplateItem::find($key_explode[1]);

                // акт осмотра, которому принадлежит создаваемый 
                // элемент акта осмотра
                $review_act = $review_acts[$review_act_template_item->review_act_template_id];
                // массив $item хранит поля создаваемого элемента акта осмотра
                $item['review_act_id'] = $review_act->id;
                $item['review_act_template_item_id'] = $key_explode[1];
                $item['status'] = $value;
                // пока что поле комментария не используется, но пусть оно будет на будущее
                $item['comment'] = /*$data['comment_'.$key_explode[1]] ? $data['comment_'.$key_explode[1]] :*/ '';
                $review_act_item = $review_act->items()->create($item);
                // изображения сохраняем только для включенных чекбоксов
                $review_act_image = 'image_' . $key_explode[1];
                if($value && !empty($data[$review_act_image])){
                    $review_act_item->syncImages($data[$review_act_image]);
                }
            }
        }
        return null;
    }

    /*
    * Обновление диагностической карты
    */
    public function update($data, $diagnostic_card)
    {
        // обновляем поля диагностической карты из вкладки "Общие"
        $diagnostic_card->update([
            'km' => $data['km'],
            'fuel' => $data['fuel'],
        ]);

        // перебор полученных полей всех актов осмотра из карты 
        foreach ($data as $key => $value) {
            $key_explode = explode('_', $key);

            if(!empty($key_explode[1]) && $key_explode[0] == 'body') {
                $review_act_item = ReviewActItem::find($key_explode[1]);

                // пока что поле комментария не используется, но пусть оно будет на будущее
                $result['comment'] = /*$data['comment_'.$key_explode[1]] ? $data['comment_'.$key_explode[1]] :*/
                    '';
                $result['status'] = $value;
                $review_act_item->update($result);
                $review_act_image = 'image_' . $key_explode[1];
                if ($value && !empty($data[$review_act_image])) {
                    $review_act_item->syncImages($data[$review_act_image]);
                } else {
                    $review_act_item->syncImages('');
                }

            }
        }

        return null;
    }


    public function delete($diagnostic_card)
    {
        $diagnostic_card->items()->delete();
        $diagnostic_card->delete();
        return null;
    }


    public function save($request, $bus, $diagnosticCard){

        try {

            $childs = collect($request->get('childs'))->map(function ($child) use ($request, $diagnosticCard) {
                $result = $request->has($child) ? $request->get($child) : [];
                $result['review_act_template_item_id'] = $child;
                $result['comment'] = !empty($result['comment']) ? $result['comment'] : '';
                $cardAct = $diagnosticCard->items()->updateOrCreate(array_only($result, ['review_act_template_item_id', 'comment']));
                $saveImage = array_has($result, 'image') ? $cardAct->syncImages($result['image']) : $cardAct->syncImages([]);
                return $cardAct->images;
            });

            $variable = $request->only(['fuel', 'odometer']);
            $variable['bus_id'] = $bus->id;
            $diagnosticCard->bus_variable()->updateOrCreate($variable);
            return true;
        } catch (\Exception $exception) {
            return false;
        }

    }

    public function saveOnlyItems($request, $diagnosticCard)
    {
        try {
            $uploadController = new UploadController();
            $childs = collect($request->get('childs'))->map(function ($child) use ($request, $diagnosticCard, $uploadController) {
                $result = $request->has($child) ? $request->get($child) : [];
                $result['review_act_template_item_id'] = $child;
                $result['comment'] = !empty($result['comment']) ? $result['comment'] : '';
                $cardAct = $diagnosticCard->items()->updateOrCreate(array_only($result, ['review_act_template_item_id', 'comment']));

                $images = [];
                if ($request->hasFile($child . '.images')) {
                    $images = $this->syncFiles($request, $cardAct, $child);
                }
                return true;
            });
            return $childs;
        } catch (\Exception $exception) {
            return false;
        }


    }


    private function syncFiles($request, $cardAct, $childId)
    {
        $files = $request->file($childId . '.images');

        $images = [];
        foreach ($files as $key => $file) {
            $filename = str_random() . '.' . $file->getClientOriginalExtension();
            $path = 'uploads' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'temp';
            $file->move($path, $filename);
            $images['images']['image']['path'][] = $path . DIRECTORY_SEPARATOR . $filename;

        }
        $saveImage = $cardAct->syncImages($images);

        return $images;
    }

}
