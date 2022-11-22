<?php
return [
    "home" => [
        "title" => "Home",
    ],
    "users" => [
        "title" => 'Users',
        "add_button" => "Add user",
        "edit_button" => "Edit user",
        'create' => 'Create user',
        'edit' => 'Edit user',
        'show' => 'View user',
        'list' => 'User list',
        'roles' => array_combine(\App\Models\Role::ROLES,[
            'Operator',
            'Admin',
            'Super admin',
            'Agent',
            '',
            ''
        ]),
        'statuses' => array_combine(\App\Models\User::STATUSES, [
            'Active',
            'Inactive',
        ]),
        'permissions' => [
        'title' => 'Permissions',
        ]
    ],
    'companies' => [
        'title' => 'Companies',
        'add_button' => 'Add company',
        'edit_button' => 'Edit company',
        'create' => 'Create a company',
        'edit' => 'Editing a company',
        'show' =>'View company',
        'List' => 'Company list',
        'statics' => 'Company statistics',
        'statuses' => array_combine(\App\Models\Company::STATUSES,[
        'Active',
        'Inactive',
            ]),
        'reputations' => array_combine(\App\Models\Company::REPUTATIONS, [
           'New',
           'Reliable',
           'Problem',
        ]),
        ],
    'buses' => [
        'title' => 'Buses',
    'add_button' => 'Add bus',
        'edit_button' => 'Edit bus',
        'create' => 'Create a bus',
        'edit' => 'Edit bus',
        'show' => 'View buses',
        'list' => 'Bus list',
        'statics' => 'Bus statistics',
        'statuses' => array_combine(\App\Models\Bus::STATUSES, [
            'Active',
            'Inactive',
            'Repairs',
            'Out of repair',
            '',
        ]),
        'repairs' => [
        'title' => 'Repairs',
            'add_button' => 'Add repair',
            'edit_button' => 'Edit repair',
            'create' => 'Creating a repair',
            'edit' => 'Editing repair',
            'list' => 'List of repairs',
            'statuses' => array_combine(\App\Models\Repair::STATUSES, [
                'Repairs',
                'Comes from repair',
                '',
                '',
                '',
                ''
            ]),
            'types' => array_combine(\App\Models\Repair::TYPES, [
                'Maintenance',
                'Accident repair',
                'Usual repair',
            ]),
            ],
        'templates' => [
            'title' => 'Location of places',
            'add_button' => 'Add template',
            'edit_button' => 'Edit template',
            'create' => 'Create a template',
            'edit' => 'Edit template',
            'list' => 'Template list',
            // 'types' => array_combine (\ App \ Models \ Template :: TYPES, [
// 'Maintenance',
// 'Emergency repair',
//]),
        ],
        ],
    'drivers' => [
        'title' => 'Drivers',
        'add_button' => 'Add driver',
        'edit_button' => 'Edit driver',
        'create' => 'Creating a driver',
        'edit' => 'Driver editing',
        'show' => 'View driver',
        'list' => 'List of drivers',
        'statics' => 'Driver statistics',
        'statuses' => array_combine(\App\Models\Company::STATUSES, [
            'Active',
            'Inactive',
        ]),
        'reputations' => array_combine(\App\Models\Company::REPUTATIONS, [
            'New',
            'Reliable',
            'Problem',
        ]),
        ],
    'routes' => [
        'title' => 'Directions',
        'add_button' => 'Add directions',
        'edit_button' => 'Edit Direction',
        'create' => 'Create a directions',
        'edit' => 'Editing directions',
        'show' => 'View direction',
        'sort' => 'Sort directions',
        'list' => 'List of directions',
        'statuses' => array_combine(\App\Models\Route::STATUSES, [
            'Active',
            'Not displayed on the site',
            'Inactive',
        ]),
        'is_international' => array_combine(\App\Models\Route::IS_INTERNATIONAL, [
            'No',
            'Yes',
        ]),
'cities' => [
    'title' => 'Cities',
    'add_button' => 'Add city',
    'edit_button' => 'Edit city',
    'create' => 'Create a city',
    'edit' => 'Editing a city',
    'list' => 'List of cities',
    'statuses' => array_combine(\App\Models\City::STATUSES, [
        'Active',
        'Inactive',
    ]),
    ],
        'streets' => [
'title' => 'Streets',
            'add_button' => 'Add street',
            'edit_button' => 'Edit street',
            'create' => 'Creating a street',
            'edit' => 'Street Editing',
            'list' => 'List of streets',
            'statuses' => array_combine(\App\Models\City::STATUSES, [
               'Active',
               'Inactive',
            ]),
            ],
            'stations' => [
                'title' => 'Bus Stops',
                'add_button' => 'Add a stop',
                'edit_button' => 'Edit stop',
                'create' => 'Create a stop',
                'edit' => 'Edit stop',
                'list' => 'List of stops',
                'statuses' => array_combine(\App\Models\Station::STATUSES, [
                    'Active',
                    'Picking up',
                    'Inactive',
                    ''
                ]),
            ],
        ],
    'schedules' => [
        'title' => 'Schedule',
        'add_button' => 'Add schedule',
        'edit_button' => 'Edit schedule',
        "create" => "Create a schedule",
        "edit" => "Editing the schedule",
        "list" => "Schedule list",
        "statuses" => array_combine(\App\Models\Schedule::STATUSES, [
            "Active",
            "Inactive",
            ''
        ]),
        "days" => array_combine(\App\Models\Schedule::DAYS, [
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday",
            "Sunday"
        ]),
    ],
    "clients" => [
        "title" => "Clients",
        "add_button" => "Add client",
        "edit_button" => "Edit client",
        "create" => "Create a client",
        "edit" => "Edit client",
        "show" => "View client",
        "List" => "Customer List",
        "statics" => "Customer statistics",
        "statuses" => array_combine(\App\Models\Client::STATUSES, [
            'Active',
            'Inactive',
        ]),
        "reputations" => array_combine(\App\Models\Client::REPUTATIONS, [
            'New',
            "Reliable",
            "Problem",
        ])
    ],
    "tours" => [
        "title" => "Trips",
        "send_sms" => "Send SMS",
        "add_button" => "Add Trip",
        "edit_button" => "Edit trip",
        "create" => "Create trip",
        "edit" => "Edit trip",
        "show" => "Review trip",
        "list" => "List of trips",
        "statuses" => array_combine(\App\Models\Tour::STATUSES, [
            "Active",
            "Inactive",
            "Repairs",
            "Duplicate",
            "Completed",
            ''
        ]),
        "driver_types" => array_combine(App\Models\Tour::TYPE_DRIVERS, [
            "New",
            "Boarding",
            "Boarding complete",
            "On my way",
            "Completed",
        ]),
    ],
    "orders" => [
        "title" => "Booking",
        "add_button" => "Add booking",
        "edit_button" => "Edit booking",
        "create" => "Create booking",
        "edit" => "Edit booking",
        "show" => "View booking",
        "list" => "Booking list",
        "statuses" => array_combine(\App\Models\Order::STATUSES,[
            "Active",
            "Inactive",
            ''
        ]),
    ],
    "reviews" => [
        "title" => "Feedbacks",
        "add_button" => "Add Feedback",
        "edit_button" => "Edit Feedback",
        "create" => "Create Feedback",
        "edit" => "Edit Feedback",
        "show" => "Feedback review",
        "list" => "list of feedbacks",
        "types" => array_combine(App\Models\Review::TYPES, [
            "Positive",
            "Negative",
        ]),
    ],
    "pulls" =>[
        "title" => "Pool"
    ],
    "settings" => [
        "title" => 'Settings',
        'edit' => 'Edit settings',
        'statuses' => [
            'title' => 'Social statuses',
            'add_button' => 'Add social status',
            'edit_button' => 'Edit social status',
            'create' => 'Create social status',
            'edit' => 'Editing social status',
            'list' => 'List of social statuses',
            'statuses' => array_combine(\App\Models\Status::STATUSES, [
                'Active',
                'Inactive',
            ]),
        ],
],

    ];