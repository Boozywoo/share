<?php

return [
    'home' => [
        'title' => 'Home',
        'yes' => 'Yes',
        'no' => 'No',
        'panel' => ' | Control Panel',
    ],

	'filter' => [
		'find' => ' Find',
		'clear' => ' Clear the filter',
		'all' => 'All',
		'exit' => 'Exit',
		'back' => 'Back',
		'edit' => 'Edit',
		'save' => ' Save',
		'delete' => 'Delete',
		'to' => 'To',
		'filter' => 'Filter',
        'take_car' => 'Take a car',
        'put_car' => 'Put the car',
        'inspect_car' => 'Inspect the car',
		'cancel' => 'Cancel',
		'go' => 'Go',
        'selected' => 'Selected',
	],

    'admin' => [
        'title' => 'Admin',
        'super' => 'Super admin',
        'operator' => 'Operator',
    ],

	'auth' => [
		'title' => 'Authorization',
		'attention' => 'Attention!',
		'security' => 'For security reasons you should come up with a new password',
		'apply' => 'Apply',
		'repeat' => 'Repeat password',
		'change' => 'Password change',
		'input' => 'Log in to control panel',
		'log_in' => 'Log in',
		'tel' => 'Phone',
		'desc' => 'Description',
		'work_tel' => 'Work phone',
		'dop_tel' => 'Additional phone',
        'tel_resp' => 'Phone responsible for buses/drivers',
        'req' => 'Requisites',
		'pass' => 'Password',
		'passport' => 'Passport',
		'add' => 'Add',
		'notification' => 'Notification',
        'day_before_med_day' => 'Powiadom dni przed końcem ubezpieczenia',
        'day_before_end_visa' => 'Powiadom dni przed końcem wizy',
        'day_before_dl' => 'Notify days before the end of the driver’s license',
        'ne_reg_question' => 'Not registered ?!',
        'registration' => 'Apply for registration',
        'registration-title' => 'Registration request',
        'have-account' => 'Do you already have an account?',
        'success-registration' => 'Your application has been sent for confirmation. Wait for notification to the mailing address. ',
        'register_error' => 'Registration error',
        'confirm_code' => 'Your registration confirmation code:',
        'error_code' => 'Invalid confirmation code!',
    ],


    'users' => [
        'title' => 'Users',
        'add_button' => 'Add user',
        'edit_button' => 'Edit user',
        'create' => 'Create user',
        'user_p' => 'User',
        'user' => 'User',
        'edit' => 'Edit user',
        'show' => 'View user',
        'list' => 'List of users',
        'call' => 'Call',
        'day' => 'Day',
        'import' => 'Users import',

        'per_of_success' => 'Number of Successful Trips',
        'per_of_not_success' => 'Number of unsuccessful trips',

        'num_of_book' => 'Number of created bookings',
        'per_of_book' => '% Created bookings for the period',
        'per_of_tur' => 'Number of appearances for the period',
        'per_of_abs' => 'Number of absences for the period',
        'statistic' => 'Statistic',
        'pays' => 'Accruals/Write-offs',
        'nothing' => 'Nothing was found for this request',
        'month' => 'Month',
        'year' => 'Year',
        'company' => 'Company',
        'route' => 'Route',
        'sum' => 'Amount',
        'num' => '№ order',
        'admin' => 'Administrator',
        'charges' => 'Charges for order',
        'accrual_for_the_month' => 'The accrual for the month',

        'delete' => 'User is deleted successfully!',
        'question_del' => 'Are you sure you want to delete the user?',

        'roles' => array_combine(\App\Models\Role::ROLES, [
            'Operator',
            'Admin',
            'Super admin',
            'Agent',
            'Methodist',
            'Mediator'
        ]),
        'statuses' => array_combine(\App\Models\User::STATUSES, [
            'Active',
            'Inactive',
        ]),
        'user_statuses' => array_combine(\App\Models\User::USER_STATUSES, [
            'Approved',
            'Denied',
            'On holiday',
        ]),
        'permissions' => [
            'title' => 'Permissions',
        ],
    	'enter_name' => 'Enter your name',
    	'select_status' => '-Select a status-',
    	'select_role' => '-Select a role-',
    	'cr_vi_ed_name' => 'Create/View/Edit',

        'sel_from_year' => '-Select year start-',
        'sel_from_mon' => '-Select month start-',
        'sel_year' => '-Select year finish-',
        'sel_mon' => '-Select year finish-',

        'unload' => 'Unload in Exel',
        'online' => 'Online order',
    ],

    'companies' => [
        'single' => 'Company',
        'title' => 'Companies',
        'add_button' => 'Add company',
        'edit_button' => 'Edit company',
        'create' => 'Create a company',
        'edit' => 'Editing a company',
        'sel_type' => '-Select the type of payment-',
        'sel_route' => '-Select directions-',
        'show' =>'View company',
        'list' => 'Company list',
        'statics' => 'Company statistics',
        'statuses' => array_combine(\App\Models\Company::STATUSES, [
            'Active',
            'Inactive',
        ]),
        'reputations' => array_combine(\App\Models\Company::REPUTATIONS, [
	        'New',
	        'Reliable',
	        'Problem',
        ]),
        'search' => 'Search for a company',
    ],

    'agreements' => [
        'title' => 'Contracts',
        'add_button' => 'Add contract',
        'edit_button' => 'Edit contract',
        'create' => 'Create contract',
        'edit' => 'Edit contract',
        'show' => 'View contract',
        'list' => 'Contract list',
    ],

    'tariffs' => [
        'title' => 'Tariffs',
        'add_button' => 'Add tariff',
        'tariff_rates' => 'Tariff rates',
        'name' => 'Tariff name',
        'type' => 'Tariff type',
        'edit_button' => 'Edit tariff',
        'create' => 'Tariff creation',
        'edit' => 'Tariff Editing',
        'show' => 'View tariff',
        'sel_type' => '-Select the type of tariff-',
        'sel_type_bus' => '-Select bus type-',
        'list' => 'Tariff list',
        'types' => array_combine(\App\Models\Tariff::TYPES, [
            'TIME TARIFF (H)',
            'TARIFF OF KM',
            'TARIFF OF DIRECTION',
        ]),

        'tariff_directions' => array_combine(\App\Models\Tariff::TARIFF_DIRECTIONS, [
            'CARRIER',
            'CLIENT'
        ]),

        'statuses' => array_combine(\App\Models\Company::STATUSES, [
            'Active',
            'Inactive',
        ]),
    ],

    'tariff_rates' => [
        'title' => 'Tariff rates',
        'add_button' => 'Add a tariff rate',
        'edit_button' => 'Edit Tariff Rate',
        'create' => 'Tariff rate creation',
        'edit' => 'Tariff rate editing',
        'show' => 'View tariff rate',
        'list' => 'List of tariff rates',

        'delete' => 'Tariff rate is deleted successfully!',
        'question_del' => 'Are you sure you want to remove the tariff rate?',
    ],

    'bus_type' => [
        'index' => 'Buses type',
        'edit' => 'Edit type',
        'create' => 'Create type',
        'title' => 'Buses type',
        'add_button' => 'Add type',
        'list' => 'List of types',
    ],

    'rents' => [
        'title' => 'Rent',
        'add_button' => 'Add rental',
        'edit_button' => 'Edit rental',
        'create' => 'Rental Creation',
        'edit' => 'Rental editing',
        'list' => 'Rental List',
        'info' => 'Tour Information',
        'not_assigned' => 'Not assigned',
        'comment' => 'Comment',
        'total' => 'Total rental amount',
        'statuses' => array_combine(\App\Models\Repair::STATUSES, [
            '',
            '',
            'Repairs',
            'From repair',
            '',
            ''
        ]),
        'types' => array_combine(\App\Models\Repair::TYPES, [
            'Maintenance',
            'Emergency repair',
            'Current',
        ]),
    ],
    'wishes'=> [
        'title' => 'Problems / wishes',
        'notify' => 'Problems / wishes (notifications)',
        'create' => 'Create',
        'edit' => 'Edit',
        'delegate' => 'Delegate',
        'search_user' => 'Search user',
    ],
    'buses' => [
        'title' => 'Buses',
        'bus' => 'Bus',
        'add_button' => 'Add bus',
        'edit_button' => 'Edit bus',
        'create' => 'Create a bus',
        'edit' => 'Edit bus',
        'show' => 'View buses',
        'list' => 'Bus list',
        'not_sel' => '- Not selected -',
        'rent_cities' => 'Rental cities',
        'repair' => 'Repair',
        'statics' => 'Bus statistics',
        'enter_name' => 'Enter the name',
        'enter_num' => 'Enter the number',
        'sel_company' => '-Select the company-',
        'sel_routes' => '-Select the route-',
        'sel_status' => '-Select the status-',
        'sel_type' => '-Select the type-',
        'total' => 'Total',
        'sel_temp' => '-Select the template-',
        'ranges' => 'Ranges - ',
        'columns' => 'Columns - ',
        'auto' => 'Automatic numbering of places',
        'hard' => 'Hard numbering',
        'add' => 'Manual add number to place',
        'letter' => 'Numbering bottom',

        'import' => 'Buses import',
        'export_stat' => 'Export to Excel',

        'delete' => 'Bus is deleted successfully!',
        'question_del' => 'Are you sure you want to remove the bus?',

        'statuses' => array_combine(\App\Models\Bus::STATUSES, [
            'Active',
            'Inactive',
            'Repairs',
            'Out of repair',
            'System',
        ]),
        'types' => array_combine(\App\Models\Bus::TYPES, [
            'Sedan',
            'Minivan',
            'Universal',
            'Bus',
        ]),
        'repairs' => [
            'title' => 'Repairs',
            'add_button' => 'Add repair',
            'edit_button' => 'Edit repair',
            'create' => 'Creating a repair',
            'edit' => 'Editing repair',
            'list' => 'List of repairs',
            'statuses' => array_combine(\App\Models\Repair::STATUSES, [
                '',
                '',
                'Repairs',
                'Comes from repair',
                '',
                ''
            ]),
            'types' => array_combine(\App\Models\Repair::TYPES, [
                'Maintenance',
                'Accident repair',
                'Usual repair',
            ]),
        ],

        'rent' => [
            'title' => 'Rent',
            'add_button' => 'Add rent',
            'edit_button' => 'Edit rent',
            'create' => 'Creating a rent',
            'edit' => 'Editing rent',
            'list' => 'List of repair',
            'info' => 'flight information',
	        'not_assigned' => 'Not assigned',
	        'comment' => 'Comment',
	        'send' => 'Send',
	        'car' => 'Car',
	        'absence' => 'Absence',
            'app' => 'Appearances',
	        'upd_data' => 'Data successfully updated',
	        'quantity' => 'Total quantity of passengers',
	        'sent_pool' => 'Send to pool',
            'clean' => 'To remove from pool',
            'all' => 'Select all',
            'total' => 'All seats',
            'empty' => 'Empty seats',
            'active' => 'Active',
            'statuses' => array_combine(\App\Models\Repair::STATUSES, [
                '',
                '',
                'Repairs',
                'Comes from repair',
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
            'download' => 'Download Template',
            'edit' => 'Edit template',
            'list' => 'Template list',

            'delete' => 'Template is deleted successfully!',
            'question_del' => 'Are you sure you want to delete the template?',
        ],
    ],

    'drivers' => [
        'title' => 'Drivers',
        'driver' => 'Driver',
        'add_button' => 'Add driver',
        'add_fine' => 'Add fine',
        'edit_fine' => 'Edit fine',
        'edit_button' => 'Edit driver',
        'create' => 'Creating a driver',
        'edit' => 'Editing of driver',
        'fines' => 'Driver fines',
        'show' => 'View driver',
        'list' => 'List of drivers',
        'date' => 'Date',
        'time' => 'Time',
        'type' => 'Type',
        'paid' => 'Paid',
        'not' => 'Not',
        'not_def' => 'Not defined',
        'enter_name' => 'Enter your name',
        'enter_city' => 'Enter city',
        'statics' => 'Driver statistics',

        'import' => 'Driver import',

        'delete' => 'Driver is deleted successfully!',
        'question_del' => 'Are you sure you want to delete the driver?',

        'statuses' => array_combine(\App\Models\Company::STATUSES, [
            'Active',
            'Inactive',
        ]),
        'reputations' => array_combine(\App\Models\Company::REPUTATIONS, [
            'New',
            'Reliable',
            'Problem',
        ]),
        'type_fines' => array_combine(\App\Models\Driver::FINE_TYPES, [
            'Light',
            'Average',
            'Critical',
        ]),
    ],

    'routes' => [
        'title' => 'Directions',
        'single' => 'Direction',
        'add_button' => 'Add directions',
        'edit_button' => 'Edit Direction',
        'create' => 'Create a directions',
        'edit' => 'Editing directions',
        'show' => 'View direction',
        'not_displayed'=> 'Not displayed on site',
        'sort' => 'Sort directions',
        'list' => 'List of directions',
        'min' => 'minute',
        'price' => 'Prices',
        'time' => 'Time in minutes from the departure from the previous item to departure from the current',
        'except_for_taxi' => 'taxi exemption',
        'boarding' => 'boarding',
        'alighting' => 'alighting',
        'disembarkation' => 'disembarkation',
        'sum' => 'The sum of all durations is the total time of the bus in transit.',
        'add' => ' Add a stop',
        'go' => 'Go to flight',
        'seats' => 'seats',
        'forced' => 'Forced replacement',
        'new' => 'New Seating',
        'seating_arrangements' => 'Seating',

        'import' => 'Routes import',

        'statuses' => array_combine(\App\Models\Route::STATUSES, [
            'Active',
            'Not displayed on the site',
            'Inactive',
        ]),
        'is_international' => array_combine(\App\Models\Route::IS_INTERNATIONAL, [
            'No',
            'Yes',
        ]),
        'discount_types' => [
            false => 'Money',
            true  => 'Percent',
        ],

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
            'title' => 'Bus stops',
            'add_button' => 'Add a stop',
            'edit_button' => 'Edit stop',
            'copy_button' => 'Copy stop',
            'create' => 'Create a stop',
            'edit' => 'Editing a stop',
            'copy' => 'Copying a stop',
            'list' => 'List of stops',
            'statuses' => array_combine(\App\Models\Station::STATUSES, [
                'Active',
                'Picking up',
                'Inactive',
                'Taxi',
            ]),
        ],
        'search' => 'Finding directions',
    ],

    'schedules' => [
        'title' => 'Schedule',
        'active' => 'Active',
        'add_button' => 'Add schedule',
        'edit_button' => 'Edit schedule',
        'create' => 'Create a schedule',
        'edit' => 'Editing the schedule',
        'list' => 'Schedule list',
        'cost' => 'Ticket cost',
        'start' => 'Schedule start date',
        'finish' => 'Schedule finish date',
        'statuses' => array_combine(\App\Models\Schedule::STATUSES, [
            'Active',
            'Inactive',
            'Virtual',
        ]),
        'days' => array_combine(\App\Models\Schedule::DAYS, [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday',
        ]),
    ],

    'clients' => [
        'title' => 'Clients',
        'client' => 'Client',
        'surname' => 'Surname',
        'name' => 'Name',
        'discounts' => 'Discounts',
        'price_without_discount' => 'Price without discount',
        'children' => 'Children',
        'edit_price' => 'Price editing',
        'patronymic' => 'Patronymic',
        'add_button' => 'Add client',
        'edit_button' => 'Edit client',
        'create' => 'Create a client',
        'edit' => 'Edit client',
        'show' => 'View client',
        'list' => 'Clients List',
        'final_price' => 'Final price',
        'tickets' => 'Tickets',
        'social' => 'Social status',
        'export' => 'Export clients',
        'enter_name' => 'Enter the name',
        'enter_surname' => 'Enter the surname',
        'enter_pass' => 'Enter a series and passport number',
        'enter_tel' => 'Enter the telephone',
        'sel_rep' => '-Select the reputation-',
        'import' => 'Import clients',
        'sel_bus' => '-Select bus-',
        'sel_num_ticket' => '-Select number of ticket-',
        'sel_city' => '-Select city-',
        'sel_driver' => '-Select the driver-',
        'statics' => 'Customer statistics',
        'delete' => 'Client successfully deleted!',
        'question_del' => 'Do you really want to delete the client?',
        'statuses' => array_combine(\App\Models\Client::STATUSES, [
            'Active',
            'Inactive',
        ]),
        'reputations' => array_combine(\App\Models\Client::REPUTATIONS, [
            'New',
            'Reliable',
            'Problem',
        ]),
    ],

    'packages' => [
        'add_button' => 'Add parcel',
    ], 

    'tours' => [
        'title' => 'Tours',
        'tour' => 'Tour',
        'active' => 'Active',
        'type' => 'Tour type',
        'send_sms' => 'Send SMS',
        'add_button' => 'Add Trip',
        'edit_button' => 'Edit tour',
        'create' => 'Create tour',
        'edit' => 'Edit tour',
        'show' => 'Review tour',
        'list' => 'List of tours',
        'statistics' => 'Tours statistics',
        'statuses' => array_combine(\App\Models\Tour::STATUSES, [
            'Active',
            'Inactive',
            'Repairs',
            'Duplicate',
            'Completed',
            'Virtual',
        ]),
        'driver_types' => array_combine(App\Models\Tour::TYPE_DRIVERS, [
            'New',
            'Boarding',
            'Boarding complete',
            'On my way',
            'Completed',
        ]),

        'import' => 'Load sorted bookings by drop-off time',
        'template' => 'Download the template for loading armor',

        'import_file' => 'Import Orders',
        'active_all' => 'Active (all)',

    ],

    'orders' => [
        'title' => 'Orders',
        'route' => 'Route',
        'slug' => 'Order Number',
        'add_button' => 'Add order',
        'edit_button' => 'Edit booordering',
        'no_driver' => 'Driver is not assigned',
        'no_bus' => 'Bus is not assigned',
        'empty_seats' => 'Empty seats',
        'change_order' => 'Change order',
        'previous_seats' => 'Previous seats',
        'seats_quantity' => 'Seats quantity',
        'cost' => 'Cost',
        'pdf' => 'Download PDF',
        'num_contract' => 'Number of contract',
        'down' => 'Download ticket',
        'sel_seats' => 'Select seats',
        'departure_time' => 'Departure time',
        'arrival_time' => 'Arrival time',
        'kids_quantity' => 'Kids quantity',
        'promo_code' => 'Promo code',
        'create' => 'Create book',
        'edit' => 'Edit order',
        'show' => 'View order',
        'list' => 'Booking list',
        'fix' => 'Fix order',
        'from' => '-From-',
        'pay' => 'To pay',
        'to' => '-To-',
        'upcoming' => 'Upcoming day',
        'created' => 'Created by day',
        'num' => 'Number',
        'name' => 'Name',
        'price' => 'Price',
        'new_price' => 'New price',
        'rolling_price' => 'Rolling price',
        'city' => 'city',
        'street' => 'st.',
        'calc' => 'Calculation',
        'save' => 'Save and create a new reservation',
        'percent' => '% per order',
        'fix_order' => 'fix for order',
        'salary' => 'salary',
        'sel_route' => '-Select route-',
        'not_confirmed' => 'Booking not confirmed',
        'paid' => 'Paid',
        'sms_sent' => 'SMS sent',
        'enter_num' => 'Enter ticket number',
        'booked_from_mobile' => 'Booked from the mobile',
        'booked_from_website' => 'Booked from the website',
        'booked_from_agent' => 'Booked from the agent',

        'clients_name' => 'Clients full name',
        'clients_phone' => 'Clients phone',
        'pay' => 'Pay',
        'date_of_creation' => 'Date of creation',
        'date_of_travel' => 'Date of travel',
        'num_of_children' => 'Number of children',

        'statuses' => array_combine(\App\Models\Order::STATUSES,[
            'Active',
            'Reserve',
            'Inactive',
        ]),
        'pay_types' => array_combine(\App\Models\Order::TYPE_PAYS, [
            'Cash payment',
            'Cancel',
            'Pending payment',
            'Online payment',
            'Payment to current account',
            '!Awaiting payment to current account',
            'Cashless payment (for driver)',
            'Cash payment at the office',
        ]),
        'flight_number' => 'Flight number:',
        'total_ticket' => 'Ticket cost',
        'add_services_total' => 'Additional services cost',
        'total_sum' => 'Total order',
    ],

    'reviews' => [
        'title' => 'Feedbacks',
        'add_button' => 'Add Feedback',
        'edit_button' => 'Edit Feedback',
        'create' => 'Create Feedback',
        'edit' => 'Edit Feedback',
        'show' => 'Feedback review',
        'list' => 'List of feedbacks',
        'types' => array_combine(App\Models\Review::TYPES, [
            'Positive',
            'Negative',
        ]),
    ],

    'pulls' => [
        'title' => 'Pool',
        'question_del' => 'Are you sure you want to delete the order?',
        'question_cancel' => 'Are you sure you want to cancel the order?',
        'client_not_create'=> 'Client not created yet',
        'order_not_found'=> 'Booking not found',
        'del_order' => 'Booking successfully deleted!',
        'cancel_order' => 'Booking successfully canceled!',
    ],

    'settings' => [
        'title' => 'Settings',
        'edit' => 'Edit settings',
        'general' => 'General settings',
        'logo' => 'Logo',

        'smsconfig' => [
          'title' => 'SMS Config',
          'edit' => 'Editing SMS Config',
          'warning' => 'Sms will show only this fields',
            'smsfields' => 'SMS Fields',
            'fields' => [
              'from' => 'From',
              'order' => 'Order',
              'to' => 'To',
              'date' => 'Date',
              'auto' => 'Auto',
              'place' => 'Place',
              'places' => 'Places',
              'places_count' => 'Count',
              'price' => 'Price',
              'driver_phone' => 'Driver',
              'start' => 'Start',
              'end' => 'Finish',
            ],
            'table' => [
                'number' => '#',
                'name' => 'Name',
                'show' => 'Show'
            ]
        ],

        'driverapp' => [
            'title' => 'Driver app settings',
        ],

        'clientsInterfaceSettings' => [
            'title' => 'Site settings',
            'list' => 'Settings List',
        ],

        'add_services' => [
            'title' => 'Add. Services',
            'add_button' => 'Add add. status',
            'edit_button' => 'Edit add. service',
            'create' => 'Create add. service',
            'edit' => 'Editing add. service',
            'list' => 'List of add. services',
            'status_not' => 'No status selected',
            'statuses' => array_combine(\App\Models\Status::STATUSES, [
                'Active',
                'Inactive',
            ]),
        ],
        'statuses' => [
            'title' => 'Social statuses',
            'add_button' => 'Add social status',
            'edit_button' => 'Edit social status',
            'create' => 'Create social status',
            'edit' => 'Editing social status',
            'list' => 'List of social statuses',
            'status_not' => 'Status not selected',
            'statuses' => array_combine(\App\Models\Status::STATUSES, [
                'Active',
                'Inactive',
            ]),
        ],
        'sales' => [
	        'title' => 'Promotions',
	        'add_button' => 'Add promotion',
	        'edit_button' => 'Edit promotion',
	        'create' => 'Create promotion',
	        'edit' => 'Edit promotion',
	        'list' => 'List of promotions',
	        'statuses' => array_combine(\App\Models\Sale::STATUSES, [
	        	'Active',
	        	'Inactive',
	        ]),
	        'types' => array_combine(\App\Models\Sale::TYPES, [
	        	'Starting with x trips',
	        	'Every x trip',
	        	'Buying x tickets one time',
	        ]),
        ],

        'interfaceSettings' => [
            'title' => 'Interface settings',
            'list' => 'List of settings',
            'color_theme' => ['Dark theme', 'Light theme', 'Not defined'],
        ],

        'coupons' => [
            'title' => 'Promo code',
            'add_button' => 'Add promo code',
            'edit_button' => 'Edit promo code',
            'create' => 'Create promo code',
            'edit' => 'Edit promo codes',
            'list' => 'Promo code list',
            'statuses' => array_combine(\App\Models\Sale::STATUSES, [
            	'Active',
            	'Inactive',
            ]),
        ],
        'notifications' => [
            'title' => 'Types of notifications',
            'list' => 'List of notification types',
            'add_button' => 'Add new type',
            'edit' => 'Editing notification type',
            'create' => 'Create notification type'

        ],
        'wishes' => [
            'title' => 'Types of application',
            'list' => 'List of application types',
            'add_button' => 'Add new type',
            'edit' => 'Editing application type',
            'create' => 'Create application type',
            'check_on_departments' => 'For the whole department or only for the manager (Yes / No)'
        ],
    ],

    'monitoring' => [
        'list' => 'Route monitoring',
        'title' => 'Monitoring',
        'sel_time' => '- Select time -',
    ],

    'partials' => [
            'main' => 'To make the main',
            'image' => 'Image',
            'activity' => 'Action',
    ],

    'pages' => [
        'title' => 'Pages',
        'main' => 'Main',
        'add_button' => 'Add page',
        'create' => 'Create a page',
        'edit' => 'Edit Page',
        'list' => 'List of Pages',
    ],

    'salary' => [
        'sum_of_orders' => 'Amount of reservation / cancellation',
        'title' => 'Salary',
        'num_of_appearances' => 'Number of appearances',
        'num_of_absenteeism' => 'Number of absenteeism',
        'bonuses' => 'Booking bonuses',
        'total' => 'Total salary',
        'paid' => 'Paid',
        'must' => 'Must be paid',
        'excess' => 'Paid in excess of',
        'add_button' => 'Add payout',
        'create' => 'Add payouts',
        'payments' => 'Payments',
    ],
    'cron' => [
        'title' => 'Cron',
        'list'  => 'CRON Job List'
    ],

    'notifications' => [
        'role_hr' => 'HR (approval of registration applications)',
        'title' => 'Notifications',
        'list' => 'List of notifications',
        'edit' => 'Notification',
        'text' => [
            'registration' => 'New user registration',
            'small_text_registration' => 'Employee registration',
            'wishes' => 'New application',
            'small_text_wishes' => 'New application "Problems and wishes"',
            'wishes_delegate' => 'The application is delegated!',
            'small_text_wishes_delegate' => 'The application "Problems and Pobozhannya" is delegated',
            'wishes_completed' => 'The application is complete!',
            'small_text_wishes_completed' => 'The application is complete!',
        ],
    ],

    'notification' => [
        'statuses' => [
            'read' => 'Read',
            'approved' => 'Approved',
            'denied' => 'Denied',
            'new' => 'New',
            'new-read' => 'New - Read',
        ],
    ],

    'exploitation' => [
        'cars' => [
            'title' => 'Take a car',
            'list' => 'My cars',
            'enter_number' => 'Enter car number',
            'enter_name' => 'Enter car brand',
            'describe' => 'Describe the condition of the vehicle',
            'take_car' => 'Take a car',
            'km' => 'Current mileage',
            'fuel' => 'Remaining fuel',
            'new_km' => 'New mileage',
            'new_fuel' => 'New remainder',
            'condition_ok' => 'OK (autofill the diagnostic inspection card and signing the acceptance certificate)',
            'condition_not_ok' => 'Out of order (fill out diagnostic checklist)',
            'car' => 'Car',
            'take_user' => 'taken by the driver',
            'with_malfunction' => 'with malfunctions',
            'max_time' => 'Maximum lease time',
            'hours' => 'hours',
            'put_car' => 'Put the car',
            'inspect_car' => 'Inspect the car',
        ]
    ],

    'operational_tasks' => [
        'title' => 'Operational tasks'
    ]
];
