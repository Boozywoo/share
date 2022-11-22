<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverAppSetting extends Model
{

    protected $fillable = [
        'time_show_driver', 'is_see_passeger_phone', 'is_accept_cashless_payment', 'is_change_price',
         'was_calling', 'notification', 'is_cancel', 'is_see_statistics', 'is_see_pay', 'is_see_map', 'is_display_cities',
         'is_display_streets', 'is_display_stations', 'is_display_finished_button', 'is_display_utc', 'default_code', 
         'is_display_first_name', 'is_display_middle_name', 'is_display_last_name', 'is_show_both_directions',
         'time_click_driver', 'is_see_passeger_passport', 'count_of_passport_digits'
    ];
}
