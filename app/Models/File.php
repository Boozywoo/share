<?php

namespace App\Models;

use App\Traits\ModelTableTrait;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use ModelTableTrait;

    /**
     * @var string[]
     */
    protected $fillable = [
        'original_name',
        'name',
        'src',
        'size',
        'extension',
        'type'
    ];
}
