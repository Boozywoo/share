<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $table = 'site_settings';

    protected $fillable = [
        'button_color', 'background_color', 'font_color', 'font_size', 'border_radius', 'font_color_authorization_buttons', 'opacity',
    ];
}
