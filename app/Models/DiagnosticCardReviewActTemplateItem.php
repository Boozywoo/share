<?php

namespace App\Models;

use App\Traits\ImageableTrait;
use Illuminate\Database\Eloquent\Model;

class DiagnosticCardReviewActTemplateItem extends Model
{
    use ImageableTrait;
    protected $fillable = ['comment','review_act_template_item_id'];

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

}
