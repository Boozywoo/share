<?php

namespace App\Services\ReviewAct;

use App\Models\ReviewAct;
use App\Models\ReviewActItem;

class ReviewActService
{

    public function store($data)
    {
        $review_act = ReviewAct::create($data);


        foreach ($data as $key => $value) {
            $key_explode = explode('_', $key);

            if ($key_explode[0] == 'body') {
                $item['review_act_template_item_id'] = $key_explode[1];
                $item['status'] = $value;
                $item['comment'] = /*$data['comment_'.$key_explode[1]] ? $data['comment_'.$key_explode[1]] :*/ '';
                $review_act_item = $review_act->items()->create($item);
                // изображения сохраняем только для включенных чекбоксов
                if($value && !empty($data['image_'.$key_explode[1]])){
                    $review_act_item->syncImages($data['image_'.$key_explode[1]]);
                }
            }
        }
        return null;
    }

    public function update($data, $review_act){
        $review_act->update($data);

        foreach ($data as $key => $value) {
            $key_explode = explode('_', $key);

            if(!empty($key_explode[1]) && $key_explode[0] == 'body'){
                $review_act_item = ReviewActItem::find($key_explode[1]);
            
                $result['comment'] = /*$data['comment_'.$key_explode[1]] ? $data['comment_'.$key_explode[1]] :*/ '';
                $result['status'] = $value;
                $review_act_item->update($result);
                if($value && !empty($data['image_'.$key_explode[1]])){
                    $review_act_item->syncImages($data['image_'.$key_explode[1]]);
                }
                else{
                    $review_act_item->syncImages('');
                }

            }
        }

        return null;
    }

    public function delete($review_act){

        $review_act->items()->delete();
        $review_act->delete();
        return null;
    }

}
