<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BgImage extends Model
{
    protected $fillable = ['ui_adm_img'];
    /**
     * Get the user backgrouund image.
     */    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
