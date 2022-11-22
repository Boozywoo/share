<?php

return [
    'admin' => [
        'auth' => [
            'login' => 'Authorization was successful!',
        ],
        'companies' => [
        	'statuses' => [
        		'disabled' => 'The company can not be made inactive, buses are on schedule',
        	],
        ],
        'drivers' => [
        	'statuses' => [
        		'disabled' => 'The driver can not be made inactive, driver is in the schedule',
        	],
        ],
        'cities' => [
        	'statuses' => [
        		'disabled' => 'The city can not be made inactive. City is assigned to stops',
        	],
        ],
        'stations' => [
        	'statuses' => [
        		'disabled' => 'Stop can not be made inactive, pointed in the direction',
        	],
        ],
		'tours' => [
			'delete' => [
				'error' => 'This Trip already has bookings. Removal is impossible! ',
			],
		],
        'order' => [
            'client_loaded' => 'Client loaded successfully',
            'client_created' => 'Client will be created',
            'stop_updated' => 'Stop updated',
            'order_deleted' => 'Order deleted',
            'client_blacklisted' => 'Blacklisted client - check status',
            'for_children' => 'All the best for children',
        ],
    ],
    'index' => [
    	'order' => [
    		'expired' => 'Booking timed out.',

            'error' => 'Error. Refresh the page!', 
            'promo_not_found' => 'No promotion code found',
            'promo_success' => 'Promo code successfully applied',
            'error_two' => 'Error. Contact your operator',
            'not_available' => 'This stop is not available for ',
            'from' => 'landing',
            'to' => 'disembarkation',
            'city_from' => 'No city from selected',
            'city_to' => 'No city to selected',
            'empty_tours' => 'No free tours found on ',
        ],
    ],

    'packages' => [
        'from' => 'The "From" field is required.',
        'destination' => 'The "To" field is required.'
    ],

];