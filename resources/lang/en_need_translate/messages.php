<?php

return [
    'admin' => [
        'auth' => [
            'login' => 'Authorization was successful!',
        ],
        'companies' => [
            'statuses' => [
                'disabled' => 'The company can not be made inactive, buses are on schedule',
            ]
        ],
        'drivers' => [
            'statuses' => [
                'disabled' => 'The driver can not be made inactive, driver is in the schedule',
            ]
        ],
        'cities' => [
            'statuses' => [
                'disabled' => 'The city can not be made inactive. City is assigned to stops',
            ]
        ],
        'stations' => [
            'statuses' => [
                'disabled' => 'Stop can not be made inactive, pointed in the direction',
            ]
        ],
        'tours' => [
            'delete' => [
                'error' => 'This Trip already has bookings . Removal is impossible!',
            ],
        ],
        'schedules' => [
            'delete' => [
                'error' => 'This schedule has Bookings . Removal is impossible!',
            ],
        ],
    ],

];