<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => 'local',

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => 's3',

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_KEY'),
            'secret' => env('AWS_SECRET'),
            'region' => env('AWS_REGION'),
            'bucket' => env('AWS_BUCKET'),
        ],

        'ftp_egis_stations' => [
            'driver' => 'ftp',
            'host' => env('EGIS_SERVER_STATIONS'),
            'username' => env('EGIS_LOGIN'),
            'password' => env('EGIS_PASS'),
            'port' => env('EGIS_PORT_STATIONS'),
             'passive' => true,
            // 'ssl' => true,
        ],

        'ftp_egis_schedules' => [
            'driver' => 'ftp',
            'host' => env('EGIS_SERVER_TIMETABLE'),
            'username' => env('EGIS_LOGIN'),
            'password' => env('EGIS_PASS'),
            'port' => env('EGIS_PORT_TIMETABLE'),
            'passive' => true,
            // 'ssl' => true,
        ],

        'ftp_egis_passengers' => [
            'driver' => 'ftp',
            'host' => env('EGIS_SERVER_PASSENGERS'),
            'username' => env('EGIS_LOGIN'),
            'password' => env('EGIS_PASS'),
            'port' => env('EGIS_PORT_PASSENGERS'),
            'passive' => true,
            // 'ssl' => true,
        ],

        'ftp_egis_feedback' => [
            'driver' => 'ftp',
            'host' => env('EGIS_SERVER_FEEDBACK'),
            'username' => env('EGIS_LOGIN'),
            'password' => env('EGIS_PASS'),
            'port' => env('EGIS_PORT_FEEDBACK'),
            'passive' => true,
            // 'ssl' => true,
        ],


    ],

    'paths' => [
        'operational_tasks' => 'operational_tasks/files'
    ]

];
