<?php

namespace App\Models;

use App\Traits\ImageableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RepairCardItem extends Model
{
    use ImageableTrait;

    protected $fillable = ['id','comment'];

    protected $table = 'repair_card_items';

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


    public function item()
    {
        return $this->hasOne(RepairCardTemplate::class, 'id', 'repair_card_template_id');
    }

}
