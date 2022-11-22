<?php

namespace App\Models;

use App\Traits\ImageableTrait;
use Illuminate\Database\Eloquent\Model;

class ReviewActItem extends Model
{
    use ImageableTrait;

    protected $fillable = ['review_act_id', 'review_act_template_item_id', 'status', 'comment'];

    const STATUS_TRUE = '1';
    const STATUS_FALSE = '';

    const STATUSES = [
        self::STATUS_TRUE,
        self::STATUS_FALSE
    ];

    const IMAGE_TYPE_IMAGE = 'image';

    const IMAGES_PARAMS = [
        self::IMAGE_TYPE_IMAGE => [
            'multiple' => true,
            'params' => [
                'admin' => [
                    'w' => 50,
                    'fit' => 'max',
                ],
            ],
        ],
    ];


    //relationships
    public function template_item()
    {

        return $this->belongsTo(ReviewActTemplateItem::class, 'review_act_template_item_id', 'id');
    }
}
