<?php

namespace App\Models;

use App\Traits\ImageableTrait;
use Illuminate\Database\Eloquent\Model;

class DiagnosticCardItem extends Model
{
    use ImageableTrait;
    protected $fillable = ['diagnostic_card_id','diagnostic_card_template_item_id','status','comment'];

    const STATUS_TRUE = 'true';
    const STATUS_FALSE = 'false';
    const STATUS_RESOLVED = 'resolved';

    const STATUSES = [
        self::STATUS_TRUE,
        self::STATUS_FALSE,
        self::STATUS_RESOLVED
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
        return $this->belongsTo(DiagnosticCardTemplateItem::class, 'diagnostic_card_template_item_id', 'id');
    }



}
