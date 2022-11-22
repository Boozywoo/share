<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class WishesComment extends Model
{
    //

    public function user()
    {
        return $this->belongsTo(User::class,'user_id', 'id');
    }

    public function files()
    {
        return $this->hasMany(WishesCommentFile::class);
    }

    public static function getListComments($wishes_id){
        return WishesComment::where('wishes_id', $wishes_id)->get();
    }
}
