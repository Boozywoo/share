<?php

Route::group([], function () {

    Route::group(['prefix' => 'auth', 'middleware' => []], function () {

        Route::post('login', 'LoginController@login');
        Route::get('check', 'LoginController@check');
        Route::post('password-forgot', 'LoginController@passwordForgot');
        Route::post('password-forgot/{code}', 'LoginController@passwordRecoveryCode');
        // Register
        Route::post('register', 'RegisterController@registration');

    });

    Route::middleware(['nortrans','api-nor-trans-auth'])->group(function () {
        Route::group(['prefix' => 'auth'], function () {
            Route::post('logout', 'LoginController@logout');
            Route::post('confirm-sms-code', 'RegisterController@registrationCode');
            Route::post('register-company', 'RegisterController@registrationCompanyStep');
            Route::post('register-department', 'RegisterController@registrationDepartmentStep');

            Route::post('password-reset', 'LoginController@passwordReset');
        });
        Route::get('user', 'LoginController@user');
        Route::get('companies', 'RegisterController@companies');
        Route::get('departments', 'RegisterController@departments');

        Route::group(['prefix' => 'garage'], function () {

            Route::group(['prefix' => 'cars'], function () {
                Route::get('/', 'CarController@index');
                Route::get('/{bus}/diagnostic_cards', 'GarageController@diagnostic_cards');
                Route::post('/{bus}/take', 'GarageController@take');
                Route::post('/{bus}/take/{userTakenBus}/finish', 'GarageController@takeFinish');
                Route::post('/{bus}/put', 'GarageController@put');
                Route::post('/{bus}/put/{userTakenBus}/finish', 'GarageController@putFinish');
                Route::post('/{bus}/review', 'GarageController@review');
            });


            Route::get('/diagnostic_card_templates', 'DiagnosticCardTemplateController@index');
            Route::get('/diagnostic_card_templates/{diagnostic_card_template_id}', 'DiagnosticCardTemplateController@show');
        });

        Route::group(['prefix' => 'diagnostic_cards'], function () {
            Route::post('/{diagnostic_card}/save-tab', 'DiagnosticCardController@saveTab');
            Route::post('/{diagnostic_card}/save', 'DiagnosticCardController@save');
        });

    });
});

