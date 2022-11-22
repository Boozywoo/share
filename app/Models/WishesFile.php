<?php

namespace App\Models;

use App\Traits\ModelTableTrait;
use Illuminate\Database\Eloquent\Model;

class WishesFile extends Model
{
    use ModelTableTrait;

    /**
     * @var string[]
     */
    protected $fillable = [
        'wishes_id',
        'original_name',
        'name',
        'src',
        'size',
        'extension',
        'type'
    ];
}
