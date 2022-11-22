<?php

namespace App\Models;

use App\Traits\ImageableTrait;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use ImageableTrait;

    protected $fillable = [
        'phone_one', 'phone_two', 'phone_tree', 'main_site', 'company_name', 'text_footer', 'field_popup_window', 'address', 'copyright',
        'account_ok', 'account_vk','account_f','account_i', 'limit_order_by_place', 'limit_order_by_count', 'order_cancel_time',
        'discount_children', 'is_notification_sms', 'auto_turn_notification', 'is_promotion_backend', 'promotion_backend_text',
        'time_hidden_tour_front', 'is_pay_on', 'time_limit_pay', 'is_pay_cash', 'limit_one_order_route', 'show_arrival_time', 'display_orders_quantity',
        'index_title', 'index_description', 'is_notification_edit_sms', 'is_notification_cancel_sms', 'allowed_ip', 'show_places_left',
        'history_days','sip_registration', 'egis_status', 'egis_file', 'egis_answer',
        'ticket_language', 'ticket_type', 'ticket_cancel_phone', 'ticket_cancel_info', 'ticket_info', 'anyway_download_tickets',
        'is_client_statistic', 'complete_tours', 'limit_booking_time', 'edit_departure_all_stations', 'is_change_in_completed_tours', 
        'is_send_to_email', 'main_email','default_timezone','send_sms_of_remove_order', 'is_change_price_agent', 'field_code_jivo',
        'turn_on_notification_if_order_paid', 'email_for_notification', 'seo_head', 'seo_body', 'display_types_of_orders', 'phone_codes',
        'is_system_paid', 'android_link', 'ios_link', 'sms_info_text',
    ];

    const IMAGE_TYPE_IMAGE = 'image';

    const IMAGES_PARAMS = [
        self::IMAGE_TYPE_IMAGE => [
            'multiple' => false,
            'params' => [
                'admin' => [
                    'w' => 50,
                    'fit' => 'max',
                ],
            ],
        ],
    ];

    public static function getField($name)
    {
        return self::first()->$name;
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function getPaymentAttribute()   // Возвращает тип оплаты - только онлайн, только наличными, или оба метода.
    {
        if ($this->is_pay_on)   {
            if ($this->is_pay_cash) {
                return 'both';
            } else {
                return 'online';
            }
        } else {
            return 'cash';
        }
    }

}
