<?php

return [
    'statuses' => [
        'active' => '<span class="text-info" data-toggle="tooltip" title="Активный"><i class="fa fa-check"></i></span>',
        'disable' => '<span class="text-danger" data-toggle="tooltip" title="Неактивный"><i class="fa fa-ban"></i></span>',
        'inactive_front' => '<span class="text-danger" data-toggle="tooltip" title="Не отображается на сайте"><i class="fa fa-eye"></i></span>',
        'collect' => '<span class="badge badge-danger" data-toggle="tooltip" title="Сбор"><i class="fa fa-car"></i></span>',
        'taxi' => '<span class="badge badge-warning" data-toggle="tooltip" title="Такси"><i class="fa fa-taxi"></i></span>',
        'waiting' => '<span class="badge badge-danger" data-toggle="tooltip" title="Сбор"><i class="fa fa-car"></i></span>',
        'repair' => '<span class="badge badge-danger" data-toggle="tooltip" title="В ремонте"><i class="fa fa-wrench"></i></span>',
        'of_repair' => '<span class="badge badge-warning" data-toggle="tooltip" title="Нужен ремонт"><i class="fa fa-wrench"></i></span>',
        'duplicate' => '<span class="badge badge-warning" data-toggle="tooltip" title="Дубликат"><i class="fa fa-files-o"></i></span>',
        'completed' => '<span class="badge badge-success" data-toggle="tooltip" title="Завершен"><i class="fa fa-hourglass-end"></i></span>',
        'no_completed' => '<span class="badge badge-warning" data-toggle="tooltip" title="Не подтверждён"><i class="fa fa-warning"></i></span>',
        'reserve' => '<span class="badge badge-primary" data-toggle="tooltip" title="Резерв"><i class="fa fa-recycle"></i></span>',
        'system' => '<span class="badge badge-success" data-toggle="tooltip" title="Системный"><i class="fa fa-support"></i></span>',
        'empty_data' => '<span class="badge badge-danger" data-toggle="tooltip" title="Данные заполнены не полностью"><i class="fa fa-warning"></i></span>',
        'virtual' => '<span class="text-info" data-toggle="tooltip" title="Виртуальный"><i class="fa fa-circle-o-notch"></i></span>',
    ],

    'pay_statuses' => [
        'waiting' => '<span class="text-warning" data-toggle="tooltip" title="В ожидании оплаты"><i class="fa fa-money"></i></span>',
        'cancel' => '<span class="text-danger" data-toggle="tooltip" title="Оплата отменена"><i class="fa fa-money"></i></span>',
        'success' => '<span class="text-info" data-toggle="tooltip" title="Оплачено онлайн"><i class="fa fa-money"></i></span>',
        'cash-payment' => '<span class="text-success" data-toggle="tooltip" title="Оплата наличными"><i class="fa fa-money"></i></span>',
        'cashless-payment' => '<span class="text-info" data-toggle="tooltip" title="Оплата по карточке водителю"><i class="fa fa-money"></i></span>',
        'checking-account' => '<span class="text-info" data-toggle="tooltip" title="Оплата на р/с"><i class="fa fa-money"></i></span>',
        'checking-account-wait' => '<span class="text-warning" data-toggle="tooltip" title="Ожидание оплаты на р/c"><i class="fa fa-warning"></i></span>',
        'cash-payment-office' => '<span class="text-success" data-toggle="tooltip" title="Оплата наличными в офисе"><i class="fa fa-money"></i></span>',
    ],

    'reputations' => [
        'new' => '<span class="default" data-toggle="tooltip" title="Новый"><i class="fa fa-circle"></i></span>',
        'reliable' => '<span class="text-info" data-toggle="tooltip" title="Надежный"><i class="fa fa-circle"></i></span>',
        'problem' => '<span class="text-danger" data-toggle="tooltip" title="Проблемный"><i class="fa fa-circle"></i></span>',
    ],
    'template_places' => [
        'order' => array_combine(\App\Models\TemplatePlace::TYPES, [
            'driverCell',
            '',
            'seat',
        ]),
    ],
    'confirm' => [
        1 => '<span class="label label-primary">Да</span>',
        0 => '<span class="label label-danger">Нет</span>',
    ],
    'egis' => [
        '1' => '<span class="text-info" data-toggle="tooltip" title="Отправка данных в ЕГИС (ФГУП «ЗащитаИнфоТранс»)"><i class="fa fa-check"></i></span>',
        '0' => '<span class="text-danger" data-toggle="tooltip" title="Данные НЕ отправляются в ЕГИС (ФГУП «ЗащитаИнфоТранс»)"><i class="fa fa-ban"></i></span>',
    ],
    'shift' => [
        1 => '<span class="text-warning" data-toggle="tooltip" title="Подменка"><i class="fa fa-exchange"></i></span>',
        0 => '',
    ],
    'tours' => [
        'types' => array_combine(\App\Models\Tour::TYPE_DRIVERS, [
            '',
            '<span class="label label-warning">Сбор</span>',
            '<span class="label label-danger">Сбор завершен</span>',
            '<span class="label label-info">В пути</span>',
            '<span class="label label-primary">Завершен</span>',
        ]),
    ],
    'rating' => '<i class="fa fa-star"></i>',
    'return-ticket' => '<i class="fa fa-repeat"></i>',
];