<?php

Route::any('telegram/web_hook', 'TelegramController@StartCommand');
Route::any('sms/web_hook', 'SmsController@webhook');
Route::any('sms/web_hook', 'SmsController@webhook');

Route::group(['prefix' => 'v1'], function () {

/*    Route::group(['namespace' => 'Admin', 'prefix' => 'registration', 'middleware' => []], function () {
        // Register
        Route::post('register', 'RegisterController@registration');
        Route::post('confirm-sms-code', 'RegisterController@registrationCode');
        Route::post('register-company', 'RegisterController@registrationCompanyStep');
        Route::post('register-department', 'RegisterController@registrationDepartmentStep');
        Route::post('get-company', 'RegisterController@companies');
        Route::post('get-department', 'RegisterController@departments');

    });

    Route::group(['namespace' => 'Admin', 'prefix' => 'auth', 'middleware' => []], function () {
        Route::post('login', 'LoginController@login');
    });*/


    Route::group(['namespace' => 'Call', 'prefix' => 'call', 'middleware' => []], function () {
        Route::any('incoming', 'CallIncomingController@index');
        Route::any('answer', 'CallAnswerController@index');
        Route::any('hangup', 'CallHangupController@index');
        Route::any('webhook', 'CallWebHookController@index');
    });


    Route::group(['namespace' => 'Driver', 'prefix' => 'driver', 'middleware' => []], function () {

        /*
         * Auth
         */
        //Route::any('auth/login', 'AuthController@login');
        Route::post('auth/login', 'AuthController@login');
        Route::group(['middleware' => 'ApiDriver'], function () {

            /*
             * Tours
             */
            Route::get('tours', 'TourController@index');
            Route::get('tours/{tour}', 'TourController@show');
            Route::post('tours/stations', 'TourController@stations');
            Route::post('tours/store', 'TourController@store');

            /*
             * Orders
             */

            Route::post('orders/store', 'OrderController@store');
            Route::post('orders/add', 'OrderController@add');
            Route::post('orders/add2', 'OrderController@add2');
            Route::post('orders/appearance', 'OrderController@appearance');
            Route::get('orders/{tour}', 'OrderController@show');

            /*
             * Clients
             */

            Route::post('clients/confirm-status', 'ClientController@confirmStatus');
            Route::post('clients/cancel-status', 'ClientController@cancelStatus');
            Route::post('clients/statuses', 'ClientController@statuses');
        });
    });


    Route::group(['namespace' => 'Client', 'prefix' => 'client', 'middleware' => []], function () {
        Route::post('auth/register', 'AuthController@register'); //
        Route::post('auth/confirm-sms-code', 'AuthController@confirmSmsCode');
        Route::post('auth/login', 'AuthController@login');
        Route::post('auth/send-sms-code-reset', 'AuthController@sendSmsCodeReset');
        Route::post('auth/confirm-sms-code-reset', 'AuthController@confirmSmsCodeReset');
        Route::post('auth/login-sms', 'AuthController@loginSendCode')->middleware(['cors']);

        Route::get('route/city-from', 'RouteController@cityFrom');
        Route::get('route/city-to', 'RouteController@cityTo');
        Route::get('route/tours', 'RouteController@tours');
        Route::get('route/taxi', 'RouteController@taxi');
        Route::get('route/transfer', 'RouteController@transfer');
        Route::post('route/tours/{tour_id}/orderPlaces', 'RouteController@storePlaces');
        Route::get('route/tours/{tour_id}/stations', 'RouteController@stations');
        Route::get('route/taxi-stations', 'RouteController@taxiStations');

        Route::get('order/list', 'OrderController@index');
        Route::get('order/show', 'OrderController@show');
        Route::get('order/last', 'OrderController@last');
        Route::post('order/info', 'OrderController@info');

        Route::post('order/add', 'OrderController@add');
        Route::post('order/cancel', 'OrderController@cancel');
        Route::post('order/confirm', 'OrderController@confirm');
        Route::post('order/update', 'OrderController@update');
        Route::post('order/pay', 'OrderController@pay');
        Route::get('order/pay-binding', 'OrderController@getBinding');
        Route::post('order/pay-binding', 'OrderController@payBinding');
        Route::get('order/get-ticket', 'OrderController@generatePdf');

        Route::post('order/taxiorder', 'OrderController@taxiOrder');
        Route::post('order/taxiorder-cancel', 'OrderController@cancelTaxiOrder');
        Route::post('order/transfer-order', 'OrderController@addTransferOrder');
        Route::get('order/get-ekam-check', 'OrderController@getEkamCheck');

        Route::put('profile/update', 'ClientController@update');
        Route::get('info', 'ClientController@info');

        Route::get('bus/show/{bus_id}', 'BusController@show');
        Route::get('bus/{bus_id}/location', 'BusController@location');
    });


    Route::group(['namespace' => 'Monitoring', 'prefix' => 'monitoring', 'middleware' => ['cors']], function () {
        Route::get('auth/login', 'AuthController@login');
        Route::get('coordinate/save', 'CoordinateController@save');
    });

});

