<?php

return [
    'statuses' => [
        'active' => '<span class="text-info" data-toggle="tooltip" title="Активний"><i class="fa fa-check"></i></span>',
        'disable' => '<span class="text-danger" data-toggle="tooltip" title="Неактивний"><i class="fa fa-ban"></i></span>',
        'inactive_front' => '<span class="text-danger" data-toggle="tooltip" title="Не відображається на сайті"><i class="fa fa-eye"></i></span>',
        'collect' => '<span class="badge badge-danger" data-toggle="tooltip" title="Збору"><i class="fa fa-taxi"></i></span>',
        'waiting' => '<span class="badge badge-danger" data-toggle="tooltip" title="Збору"><i class="fa fa-taxi"></i></span>',
        'repair' => '<span class="badge badge-danger" data-toggle="tooltip" title="Ремонт"><i class="fa fa-wrench"></i></span>',
        'of_repair' => '<span class="badge badge-warning" data-toggle="tooltip" title="З ремонту"><i class="fa fa-wrench"></i></span>',
        'duplicate' => '<span class="badge badge-warning" data-toggle="tooltip" title="Дублікат"><i class="fa fa-files-o"></i></span>',
        'completed' => '<span class="badge badge-success" data-toggle="tooltip" title="Завершений"><i class="fa fa-hourglass-end"></i></span>',
        'no_completed' => '<span class="badge badge-warning" data-toggle="tooltip" title="Не підтверджено"><i class="fa fa-warning"></i></span>',
        'reserve' => '<span class="badge badge-primary" data-toggle="tooltip" title="Резерв"><i class="fa fa-recycle"></i></span>',
        'system' => '<span class="badge badge-success" data-toggle="tooltip" title="Системний"><i class="fa fa-support"></i></span>',
        'empty_data' => '<span class="badge badge-danger" data-toggle="tooltip" title="Дані заповнені не повністю"><i class="fa fa-warning"></i></span>',
    ],

    'pay_statuses' => [
        'waiting' => '<span class="text-warning" data-toggle="tooltip" title="В очікуванні оплати"><i class="fa fa-money"></i></span>',
        'cancel' => '<span class="text-danger" data-toggle="tooltip" title="Оплата скасована"><i class="fa fa-money"></i></span>',
        'success' => '<span class="text-warning" data-toggle="tooltip" title="Оплата-онлайн"><i class="fa fa-money"></i></span>',
        'cash-payment' => '<span class="text-success" data-toggle="tooltip" title="Оплата готівкою"><i class="fa fa-money"></i></span>',
        'cashless-payment' => '<span class="text-success" data-toggle="tooltip" title="Безготівкова оплата водієві"><i class="fa fa-money"></i></span>',
        'checking-account' => '<span class="text-success" data-toggle="tooltip" title="Оплата на розрахунковий рахунок"><i class="fa fa-money"></i></span>',
        'checking-account-wait' => '<span class="text-warning" data-toggle="tooltip" title="Очікування оплати на розрахунковий рахунок"><i class="fa fa-warning"></i></span>',
    ],

    'reputations' => [
        'new' => '<span class="default" data-toggle="tooltip" title="Новий"><i class="fa fa-circle"></i></span>',
        'reliable' => '<span class="text-info" data-toggle="tooltip" title="Надійний"><i class="fa fa-circle"></i></span>',
        'problem' => '<span class="text-danger" data-toggle="tooltip" title="Проблемний"><i class="fa fa-circle"></i></span>',
    ],
    'template_places' => [
        'order' => array_combine(\App\Models\TemplatePlace::TYPES, [
            'driverCell',
            '',
            'seat',
        ]),
    ],
    'confirm' => [
        1 => '<span class="label label-primary">Так</span>',
        0 => '<span class="label label-danger">Ні</span>',
    ],
    'shift' => [
        1 => '<span class="text-warning" data-toggle="tooltip" title="Підміна"><i class="fa fa-exchange"></i></span>',
        0 => '',
    ],
    'tours' => [
        'types' => array_combine(\App\Models\Tour::TYPE_DRIVERS, [
            '',
            '<span class="label label-warning">Збору</span>',
            '<span class="label label-danger">Збір завершено</span>',
            '<span class="label label-info">У дорогу</span>',
            '<span class="label label-primary">Завершений</span>',
        ]),
    ],
    'rating' => '<i class="fa fa-star"></i>',
    'return-ticket' => '<i class="fa fa-repeat"></i>',
];