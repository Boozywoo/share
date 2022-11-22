<?php

return [
    'statuses' => [
        'active' => '<span class="text-info" data-toggle="tooltip" title="Aktywny"><i class="fa fa-check"></i></span>',
        'disable' => '<span class="text-danger" data-toggle="tooltip" title="Nieaktywny"><i class="fa fa-ban"></i></span>',
        'inactive_front' => '<span class="text-danger" data-toggle="tooltip" title="Nie jest wyświetlany na stronie"><i class="fa fa-eye"></i></span>',
        'collect' => '<span class="badge badge-danger" data-toggle="tooltip" title="Zbieranie"><i class="fa fa-taxi"></i></span>',
        'waiting' => '<span class="badge badge-danger" data-toggle="tooltip" title="Zbieranie"><i class="fa fa-taxi"></i></span>',
        'repair' => '<span class="badge badge-danger" data-toggle="tooltip" title="Naprawa"><i class="fa fa-wrench"></i></span>',
        'of_repair' => '<span class="badge badge-warning" data-toggle="tooltip" title="Z naprawy"><i class="fa fa-wrench"></i></span>',
        'duplicate' => '<span class="badge badge-warning" data-toggle="tooltip" title="Kopia"><i class="fa fa-files-o"></i></span>',
        'completed' => '<span class="badge badge-success" data-toggle="tooltip" title="Zakończony"><i class="fa fa-hourglass-end"></i></span>',
        'no_completed' => '<span class="badge badge-warning" data-toggle="tooltip" title="Nie potwierdzone"><i class="fa fa-warning"></i></span>',
        'reserve' => '<span class="badge badge-primary" data-toggle="tooltip" title="Rezerwa"><i class="fa fa-recycle"></i></span>',
        'system' => '<span class="badge badge-success" data-toggle="tooltip" title="Systemowy"><i class="fa fa-support"></i></span>',
        'empty_data' => '<span class="badge badge-danger" data-toggle="tooltip" title="Dane nie są w pełni wypełnione"><i class="fa fa-warning"></i></span>',
    ],

    'pay_statuses' => [
        'waiting' => '<span class="text-warning" data-toggle="tooltip" title="W oczekiwaniu na płatności"><i class="fa fa-money"></i></span>',
        'cancel' => '<span class="text-danger" data-toggle="tooltip" title="Płatność anulowana"><i class="fa fa-money"></i></span>',
        'success' => '<span class="text-warning" data-toggle="tooltip" title="Płatność-online"><i class="fa fa-money"></i></span>',
        'cash-payment' => '<span class="text-success" data-toggle="tooltip" title="Płatność gotówką"><i class="fa fa-money"></i></span>',
        'cashless-payment' => '<span class="text-success" data-toggle="tooltip" title="Bezgotówkowe płatności kierowcy"><i class="fa fa-money"></i></span>',
        'checking-account' => '<span class="text-success" data-toggle="tooltip" title="Płatność na konto"><i class="fa fa-money"></i></span>',
        'checking-account-wait' => '<span class="text-warning" data-toggle="tooltip" title="Oczekiwanie na płatność na konto"><i class="fa fa-warning"></i></span>',
    ],

    'reputations' => [
        'new' => '<span class="default" data-toggle="tooltip" title="Nowoczesny"><i class="fa fa-circle"></i></span>',
        'reliable' => '<span class="text-info" data-toggle="tooltip" title="Niezawodny"><i class="fa fa-circle"></i></span>',
        'problem' => '<span class="text-danger" data-toggle="tooltip" title="Problemowy"><i class="fa fa-circle"></i></span>',
    ],
    'template_places' => [
        'order' => array_combine(\App\Models\TemplatePlace::TYPES, [
            'driverCell',
            '',
            'seat',
        ]),
    ],
    'confirm' => [
        1 => '<span class="label label-primary">Tak</span>',
        0 => '<span class="label label-danger">Nie</span>',
    ],
    'shift' => [
        1 => '<span class="text-warning" data-toggle="tooltip" title="Sfałszowanie"><i class="fa fa-exchange"></i></span>',
        0 => '',
    ],
    'tours' => [
        'types' => array_combine(\App\Models\Tour::TYPE_DRIVERS, [
            '',
            '<span class="label label-warning">Zbieranie</span>',
            '<span class="label label-danger">Zbiórka zakończona</span>',
            '<span class="label label-info">W drodze</span>',
            '<span class="label label-primary">Zakończony</span>',
        ]),
    ],
    'rating' => '<i class="fa fa-star"></i>',
    'return-ticket' => '<i class="fa fa-repeat"></i>',
];