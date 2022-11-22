<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class WishesCommentFile extends Model
{
    protected $fillable = [
        'type', 'file', 'wishes_comment_id', 'originalName',
    ];

    public static function getFileType($extension)
    {
        $extensionsImage = ['jpeg','png','jpg','gif','svg'];
        if(in_array($extension, $extensionsImage)){
            return 'image';
        }
        return 'file';
    }
}
