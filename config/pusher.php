<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the connections below you wish to use as
    | your default connection for all work. Of course, you may use many
    | connections at once using the manager class.
    |
    */

    'default' => 'main',

    /*
    |--------------------------------------------------------------------------
    | Pusher Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Example
    | configuration has been included, but you may add as many connections as
    | you would like.
    |
    */

    'connections' => [
        'main' => [
			'auth_key' => env('PUSHER_APP_KEY'),
	        'secret' => env('PUSHER_APP_SECRET'),
	        'app_id' => env('PUSHER_APP_ID'),
            // 'auth_key' => 'c3a62f6331e2e8f12695',
            // 'secret' => 'a0b15b738e0b5862a203',
            // 'app_id' => '357035',
            'options' => [
                'cluster' => 'eu',
                'encrypted' => true
            ],
            'host' => null,
            'port' => null,
            'timeout' => null
        ],

        'alternative' => [
            'auth_key' => 'your-auth-key',
            'secret' => 'your-secret',
            'app_id' => 'your-app-id',
            'options' => [],
            'host' => null,
            'port' => null,
            'timeout' => null
        ],

    ]

];
