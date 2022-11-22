<?php

return [
    'statuses' => [
        'active' => '<span class="text-info" data-toggle="tooltip" title="Active"><i class="fa fa-check"></i></span>',
        'disable' => '<span class="text-danger" data-toggle="tooltip" title="Inactive"><i class="fa fa-ban"></i></span>',
        'inactive_front' => '<span class="text-danger" data-toggle="tooltip" title="Not displayed on the site"><i class="fa fa-eye"></i></span>',
        'collect' => '<span class="badge badge-danger" data-toggle="tooltip" title="Collection"><i class="fa fa-taxi"></i></span>',
        'waiting' => '<span class="badge badge-danger" data-toggle="tooltip" title="Collection"><i class="fa fa-taxi"></i></span>',
        'repair' => '<span class="badge badge-danger" data-toggle="tooltip" title="Repairs"><i class="fa fa-wrench"></i></span>',
        'of_repair' => '<span class="badge badge-warning" data-toggle="tooltip" title="From repair"><i class="fa fa-wrench"></i></span>',
        'duplicate' => '<span class="badge badge-warning" data-toggle="tooltip" title="Duplicate"><i class="fa fa-files-o"></i></span>',
        'system' => '<span class="badge badge-success" data-toggle="tooltip" title="System"><i class="fa fa-support"></i></span>',
        'completed' => '<span class="badge badge-success" data-toggle="tooltip" title="Is completed"><i class="fa fa-hourglass-end"></i></span>',
    ],
    'pay_statuses' => [
        'waiting' => '<span class="text-warning" data-toggle="tooltip" title="Pending payment"><i class="fa fa-money"></i></span>',
        'cancel' => '<span class="text-danger" data-toggle="tooltip" title="Payment canceled"><i class="fa fa-money"></i></span>',
        'success' => '<span class="text-warning" data-toggle="tooltip" title="Online payment"><i class="fa fa-money"></i></span>',
        'cash-payment' => '<span class="text-success" data-toggle="tooltip" title="Cash payment"><i class="fa fa-money"></i></span>',
        'cashless-payment' => '<span class="text-warning" data-toggle="tooltip" title="Cashless payment to the driver"><i class="fa fa-money"></i></span>',
        'checking-account' => '<span class="text-warning" data-toggle="tooltip" title="Payment to current account"><i class="fa fa-money"></i></span>',
        'checking-account-wait' => '<span class="text-warning" data-toggle="tooltip" title="Awaiting payment to current account"><i class="fa fa-warning"></i></span>',
    ],

    'reputations' => [
        'new' => '<span class="default" data-toggle="tooltip" title="New"><i class="fa fa-circle"></i></span>',
        'reliable' => '<span class="text-info" data-toggle="tooltip" title="Reliable"><i class="fa fa-circle"></i></span>',
        'problem' => '<span class="text-danger" data-toggle="tooltip" title="Problem"><i class="fa fa-circle"></i></span>',
    ],
    'template_places' => [
        'order' => array_combine(\App\Models\TemplatePlace::TYPES, [
            'driverCell',
            '',
            'seat',
        ]),
    ],
    'confirm' => [
        1 => '<span class="label label-primary">Yes</span>',
        0 => '<span class="label label-danger">No</span>',
    ],
    'shift' => [
        1 => '<span class="text-warning" data-toggle="tooltip" title="Substitution"><i class="fa fa-exchange"></i></span>',
        0 => '',
    ],
    'tours' => [
        'types' => array_combine(\App\Models\Tour::TYPE_DRIVERS, [
            '',
            '<span class="label label-warning">Collection</span>',
            '<span class="label label-danger">Collection completed</span>',
            '<span class="label label-info">On the way</span>',
            '<span class="label label-primary">Is completed</span>',
        ]),
    ],
    'rating' => '<i class="fa fa-star"></i>',
];