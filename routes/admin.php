<?php


//Auth
Route::group(['as' => 'auth.', 'prefix' => 'auth'], function () {
    Route::get('login', 'Auth\AuthController@login')->name('login');
    Route::post('login', 'Auth\AuthController@doLogin')->name('doLogin');


    Route::get('popup-registration', 'Auth\RegisterController@popupRegister')->name('popup_registration');
    Route::post('registration', 'Auth\RegisterController@registration')->name('registration');
    Route::post('registration-code', 'Auth\RegisterController@registrationCode')->name('code_registration');
    Route::get('register-success', 'Auth\RegisterController@popupSuccessRegister')->name('success_register');
    Route::get('registration-code', 'Auth\RegisterController@popupCodeRegister')->name('register_code');
    Route::get('search-company', 'Auth\RegisterController@searchCompany')->name('search_company');
    Route::get('search-director', 'Auth\RegisterController@searchDirector')->name('search-director');
    Route::get('company-role', 'Auth\RegisterController@companyRole')->name('search_role_company');
    Route::get('company-position', 'Auth\RegisterController@companyPosition')->name('search_position_company');

});

//Sms
Route::group(['prefix' => 'sms', 'as' => 'sms.'], function () {
    Route::get('/send_actual_order', 'SmsController@sendActualOrder')->name('send_actual_order');
    Route::get('/get_callback_mts', 'SmsController@getCallbackMTS')->name('get_callback_mts');
});


//Route::group(['middleware' => ['auth', 'role:admin']], function () {
Route::group([], function () {
    //Auth
    Route::group(['as' => 'auth.', 'prefix' => 'auth'], function () {
        Route::get('change-password', 'Auth\AuthController@changePassword')->name('changePassword');
        Route::post('change-password', 'Auth\AuthController@doChangePassword')->name('doChangePassword');
        Route::get('logout', 'Auth\AuthController@logout')->name('logout');
    });

    Route::group(['middleware' => ['auth']], function () {
        //Asset::add('jquery', 'admin/js/app/components/pusher.min.js');
        //Asset::add('jquery', 'admin/js/app/pusher.js');

        Route::get('/', 'HomeController@index')->name('home');
        Route::post('reorder', 'HomeController@reorder');
        Route::get('localization/{locale}', 'HomeController@localization');
        Route::post('change_background_image', 'ChangeImageController@change_background_image');
        Route::get('check_is_pay', 'HomeController@isPaySystem');

        //Users
        Route::group(['prefix' => 'users', 'as' => 'users.', 'middleware' => ['permission:view.users']], function () {
            Route::get('/', 'UserController@index')->name('index');
            Route::get('create', 'UserController@create')->name('create');
            Route::get('statistic', 'UserController@statistic')->name('statistic');
            Route::get('statistic/excel', 'UserController@statisticExcel')->name('statistic.excel');
            Route::post('store', 'UserController@store')->name('store');
            Route::post('import', 'UserController@import')->name('import');
            Route::get('show/print/template', 'UserController@print_page_template_excel')->name('show.template');
            Route::get('coordinate/is-high-speed', 'UserController@isDriverExceededSpeed');
            Route::get('getDepartments', 'UserController@getDepartments')->name('getDepartments');
            Route::get('getPositions', 'UserController@getPositions')->name('getPositions');
            Route::get('getSuperiors', 'UserController@getSuperiors')->name('getSuperiors');

            Route::group(['prefix' => '{user}'], function () {
                Route::get('set-buses-popup', 'UserController@setBusesPopup')->name('set-buses-popup');
                Route::post('set-user-buses', 'UserController@setUserBuses')->name('set-user-buses');

                Route::get('edit', 'UserController@edit')->name('edit');
                Route::get('delete', 'UserController@delete')->name('delete');
                Route::get('pays', 'UserController@pays')->name('pays');
                Route::get('pays/excel', 'UserController@paysExcel')->name('pays.excel');
            });

            //Permissions
            Route::group(['prefix' => 'permissions', 'as' => 'permissions.'], function () {
                Route::get('/', 'PermissionController@index')->name('index');
                Route::post('store', 'PermissionController@store')->name('store');
            });
        });

        Route::group(['prefix' => 'salary', 'as' => 'salary.'], function () {
            Route::get('{user}/create', 'SalaryController@create')->name('create');
            Route::post('store', 'SalaryController@store')->name('store');
        });

        //Reviews
        Route::group(['prefix' => 'reviews', 'as' => 'reviews.', 'middleware' => ['permission:view.reviews']], function () {
            Route::get('/', 'ReviewController@index')->name('index');
        });


        //Agreements
        Route::group(['prefix' => 'agreements', 'as' => 'agreements.', 'middleware' => ['permission:view.agreements']], function () {
            Route::get('/', 'AgreementController@index')->name('index');
            Route::get('statics', 'AgreementController@statics')->name('statics');
            Route::get('create', 'AgreementController@create')->name('create');
            Route::post('store', 'AgreementController@store')->name('store');
            Route::get('showPopup/{agreement?}', 'AgreementController@showPopup')->name('showPopup');
            Route::group(['prefix' => '{agreement}'], function () {
                Route::get('edit', 'AgreementController@edit')->name('edit');
                Route::get('delete', 'AgreementController@delete')->name('delete');
                Route::get('tariffs', 'AgreementController@tariffs')->name('tariffs');
                Route::get('create_tariff', 'AgreementController@createTariff')->name('create.tariff');
            });
        });

        //Tariffs
        Route::group(['prefix' => 'tariffs', 'as' => 'tariffs.', 'middleware' => ['permission:view.tariffs']], function () {
            Route::get('/', 'TariffController@index')->name('index');
            Route::get('statics', 'TariffController@statics')->name('statics');
            Route::get('create', 'TariffController@create')->name('create');
            Route::post('store', 'TariffController@store')->name('store');
            Route::get('get_min_value', 'TariffController@getMinValue')->name('get_min_value');
            Route::get('get_routes', 'TariffController@getRoutes')->name('get_routes');
            Route::group(['prefix' => '{tariff}'], function () {
                Route::get('edit', 'TariffController@edit')->name('edit');
                Route::get('delete', 'TariffController@delete')->name('delete');
                Route::get('rates', 'TariffController@rates')->name('rates');
                Route::get('create_rate', 'TariffController@createRate')->name('create.rate');
            });
        });

        //tariff_rates
        Route::group(['prefix' => 'tariff_rates', 'as' => 'tariff_rates.', 'middleware' => ['permission:view.tariffs']], function () {
            Route::post('store', 'TariffRateController@store')->name('store');
            Route::get('get_min_value', 'TariffRateController@getMinValue')->name('get_min_value');
            Route::group(['prefix' => '{rate}'], function () {
                Route::get('edit', 'TariffRateController@edit')->name('edit');
                Route::get('delete', 'TariffRateController@delete')->name('delete');
            });
        });


        //Companies
        Route::group(['prefix' => 'companies', 'as' => 'companies.', 'middleware' => ['permission:view.companies']], function () {
            Route::get('/', 'CompanyController@index')->name('index');
            Route::get('statics', 'CompanyController@statics')->name('statics');
            Route::get('create', 'CompanyController@create')->name('create');
            Route::post('store', 'CompanyController@store')->name('store');
            Route::get('showPopup/{company?}', 'CompanyController@showPopup')->name('showPopup');
            Route::post('set-department-bus/{department}', 'DepartmentController@setDepartmentBus')->name('set-department-bus');

            Route::group(['prefix' => '{company}'], function () {
                Route::get('edit', 'CompanyController@edit')->name('edit');
                Route::get('statics', 'CompanyController@companyStatics')->name('companyStatics');
                Route::get('staticsExcel', 'CompanyController@companyStaticsExcel')->name('companyStaticsExcel');
                Route::group(['prefix' => 'departments', 'as' => 'departments.'], function () {
                    Route::get('/', 'DepartmentController@index')->name('index');
                    Route::get('create', 'DepartmentController@create')->name('create');
                    Route::post('store', 'DepartmentController@store')->name('store');
                    Route::group(['prefix' => '{department}'], function () {
                        Route::get('edit', 'DepartmentController@edit')->name('edit');
                        Route::get('set-department-popup', 'DepartmentController@setDepartmentPopup')->name('set-department-popup');
                        Route::get('list', 'DepartmentController@list')->name('list');
                    });
                });
                Route::group(['prefix' => 'positions', 'as' => 'positions.'], function () {
                    Route::get('/', 'PositionController@index')->name('index');
                    Route::get('create', 'PositionController@create')->name('create');
                    Route::post('store', 'PositionController@store')->name('store');
                    Route::group(['prefix' => '{position}'], function () {
                        Route::get('edit', 'PositionController@edit')->name('edit');
                        Route::get('list', 'PositionController@list')->name('list');
                    });
                });

            });
        });

        // Problems Module
        Route::group(['prefix' => 'wishes', 'as' => 'wishes.'], function () {
            Route::group(['middleware' => ['permission:view.wishes']], function () {
                Route::get('create', 'WishesController@create')->name('create');
                Route::get('/{status?}', 'WishesController@index')->name('index');
                Route::post('store', 'WishesController@store')->name('store');
                Route::group(['prefix' => '{wishes}'], function () {
                    Route::get('complete', 'WishesController@complete')->name('complete');
                    Route::post('complete/store', 'WishesController@completeStore')->name('completeStore');

                    Route::post('changeStatus/{status?}', 'WishesController@changeStatus')->name('changeStatus');
                    Route::post('newComment', 'WishesController@newComment')->name('newComment');
                    Route::get('edit', 'WishesController@edit')->name('edit');
                    Route::get('delegate', 'WishesController@delegate')->name('delegate');
                    Route::post('delegate', 'WishesController@delegateStore')->name('delegate');
                    Route::get('delete', 'WishesController@delete')->name('delete');
                });
            });
        });

        //Buses
        Route::group(['prefix' => 'buses', 'as' => 'buses.'], function () {
            Route::group(['middleware' => ['permission:view.buses']], function () {
                Route::get('/', 'BusController@index')->name('index');
                Route::get('statics', 'BusController@statics')->name('statics');
                Route::get('export', 'BusController@export')->name('export');
                Route::get('create', 'BusController@create')->name('create');
                Route::post('store', 'BusController@store')->name('store');
                Route::get('show/print/template', 'BusController@print_page_template_excel')->name('show.template');
                Route::post('import', 'BusController@import')->name('import');
                Route::post('import-nort', 'BusController@importNorT')->name('import-nort');
                Route::get('selectTemplates', 'BusController@selectTemplates')->name('selectTemplates');
                Route::get('getTemplateCountPlaces', 'BusController@getTemplateCountPlaces')->name('getTemplateCountPlaces');
                Route::group(['prefix' => '{bus}'], function () {
                    Route::get('edit', 'BusController@edit')->name('edit');
                    Route::get('delete', 'BusController@delete')->name('delete');
                    Route::get('showPopup', 'BusController@showPopup')->name('showPopup');
                    Route::get('set-bus-popup', 'BusController@setBusPopup')->name('set-bus-popup');
                    Route::post('set-bus-department', 'BusController@setBusDepartment')->name('set-bus-department');
                    Route::get('set-users-popup', 'BusController@setUsersPopup')->name('set-users-popup');
                    Route::post('set-bus-users', 'BusController@setBusUsers')->name('set-bus-users');

                });
            });

            //Rent
            Route::group(['prefix' => 'rent', 'as' => 'rent.'], function () {
                Route::get('/', 'BusRentController@index')->name('index');
                Route::get('create', 'BusRentController@create')->name('create');
                Route::post('store', 'BusRentController@store')->name('store');
                Route::group(['prefix' => '{rent}'], function () {
                    Route::get('edit', 'BusRentController@edit')->name('edit');
                });
            });
            //Repairs
            Route::group(['prefix' => 'repairs', 'as' => 'repairs.', 'namespace' => 'Repair', 'middleware' => ['permission:view.repairs']], function () {
                Route::get('/', 'CarRepairController@index')->name('index');
                Route::get('create', 'CarRepairController@create')->name('create');
                Route::post('store', 'CarRepairController@store')->name('store');
                Route::group(['prefix' => '{repair}'], function () {
                    Route::get('edit', 'CarRepairController@edit')->name('edit');
                });
            });

            //Diagnostic Cards
            Route::group(['prefix' => '{bus}/diagnostic_cards', 'as' => 'diagnostic_cards.', 'middleware' => ['permission:view.diagnosticcards']], function () {
                Route::get('getItemsOfTemplate', 'DiagnosticCardController@getItemsOfTemplate')->name('itemsOfTemplate');
            });


            //Templates
            Route::group(['prefix' => 'templates', 'as' => 'templates.', 'middleware' => ['permission:view.templates']], function () {
                Route::get('/', 'TemplateController@index')->name('index');
                Route::get('create', 'TemplateController@create')->name('create');
                Route::post('store', 'TemplateController@store')->name('store');
                Route::group(['prefix' => '{template}'], function () {
                    Route::get('edit', 'TemplateController@edit')->name('edit');
                    Route::get('delete', 'TemplateController@delete')->name('delete');
                });
            });
        });
        Route::resource('buses.diagnostic_cards', 'DiagnosticCardController', ['middleware' => ['permission:view.diagnosticcards']]);

        // Noti
        Route::group(['prefix' => 'notifications', 'as' => 'notifications.', 'middleware' => ['permission:view.notifications']], function () {
            Route::get('/', 'NotificationController@index')->name('noti-index');
            Route::get('{notification}/edit', 'NotificationController@edit')->name('noti-edit');
            Route::get('{notification}/approved-user', 'NotificationController@approvedUser')->name('noti-approved-user');
            Route::get('{notification}/denied', 'NotificationController@denied')->name('noti-denied');
            Route::post('{notification}/read', 'NotificationController@read')->name('noti-read');
            Route::get('count', 'NotificationController@count')->name('noti-count');
        });

        //Drivers
        Route::group(['prefix' => 'drivers', 'as' => 'drivers.', 'middleware' => ['permission:view.drivers']], function () {
            Route::get('/', 'DriverController@index')->name('index');
            Route::get('statics', 'DriverController@statics')->name('statics');
            Route::get('statics/{driver}/pays', 'DriverController@pays')->name('pays');
            Route::get('create', 'DriverController@create')->name('create');
            Route::post('store', 'DriverController@store')->name('store');
            Route::post('import', 'DriverController@import')->name('import');
            Route::get('show/print/template', 'DriverController@print_page_template_excel')->name('show.template');
            Route::post('store_fine', 'DriverController@store_fine')->name('store_fine');
            Route::group(['prefix' => '{driver}'], function () {
                Route::get('edit', 'DriverController@edit')->name('edit');
                Route::get('fines', 'DriverController@fines')->name('fines');
                Route::get('delete', 'DriverController@delete')->name('delete');
                Route::get('add_fine', 'DriverController@add_fine')->name('add_fine');
                Route::get('edit/{fine}', 'DriverController@edit_fine')->name('edit_fine');

                Route::get('set-buses-popup', 'DriverController@setBusesPopup')->name('set-buses-popup');
                Route::post('set-user-buses', 'DriverController@setUserBuses')->name('set-user-buses');

            });
        });

        //Pages
        Route::group(['prefix' => 'pages', 'as' => 'pages.', 'middleware' => ['permission:view.pages']], function () {
            Route::get('/', 'PageController@index')->name('index');
            Route::get('statics', 'PageController@statics')->name('statics');
            Route::get('create', 'PageController@create')->name('create');
            Route::post('store', 'PageController@store')->name('store');
            Route::group(['prefix' => '{page}'], function () {
                Route::get('edit', 'PageController@edit')->name('edit');
                Route::get('delete', 'PageController@edit')->name('delete');
            });
        });

        //Routes
        Route::group(['prefix' => 'routes', 'as' => 'routes.'], function () {
            Route::group(['middleware' => ['permission:view.routes']], function () {
                Route::get('/', 'RouteController@index')->name('index');
                Route::get('create', 'RouteController@create')->name('create');
                Route::get('sort', 'RouteController@sort')->name('sort');
                Route::post('sort', 'RouteController@sortSave')->name('sort');
                Route::post('store', 'RouteController@store')->name('store');
                Route::post('import', 'RouteController@import')->name('import');
                Route::get('show/print/template', 'RouteController@print_page_template_excel')->name('show.template');
                Route::post('egis-send', 'RouteController@sendEgis')->name('egis_send');
                Route::post('egis-status', 'RouteController@egisStatus')->name('egis_status');
                Route::get('store-station-price', 'RouteController@storeStationPrice')->name('storeStationPrice');
                Route::get('store-all-station-price', 'RouteController@storeAllStationPrice')->name('storeAllStationPrice');
                Route::get('store-from-station-price', 'RouteController@storeFromStationPrice')->name('storeFromStationPrice');
                Route::get('store-to-station-price', 'RouteController@storeToStationPrice')->name('storeToStationPrice');
                Route::get('info/{route?}', 'RouteController@info')->name('info');
                Route::group(['prefix' => '{route}'], function () {
                    Route::get('edit', 'RouteController@edit')->name('edit');
                    Route::get('statics', 'RouteController@statics')->name('statics');
                    Route::get('prices', 'RouteController@prices')->name('prices');
                    Route::get('intervals', 'RouteController@intervals')->name('intervals');
                    Route::get('setUser', 'RouteController@setUserPopup')->name('setUserPopup');
                    Route::post('setUser', 'RouteController@setUser')->name('setUser');
                });
            });

            //Cities
            Route::group(['prefix' => 'cities', 'as' => 'cities.', 'middleware' => ['permission:view.cities']], function () {
                Route::get('/', 'CityController@index')->name('index');
                Route::get('create', 'CityController@create')->name('create');
                Route::post('store', 'CityController@store')->name('store');
                Route::group(['prefix' => '{city}'], function () {
                    Route::get('edit', 'CityController@edit')->name('edit');
                    Route::get('delete', 'CityController@delete')->name('delete');
                });
            });

            //Streets
            Route::group(['prefix' => 'streets', 'as' => 'streets.', 'middleware' => ['permission:view.cities']], function () {
                Route::get('/', 'StreetController@index')->name('index');
                Route::get('create', 'StreetController@create')->name('create');
                Route::post('store', 'StreetController@store')->name('store');
                Route::get('json', 'StreetController@jsonData')->name('json');
                Route::group(['prefix' => '{street}'], function () {
                    Route::get('edit', 'StreetController@edit')->name('edit');
                });
            });

            //Stations
            Route::group(['prefix' => 'stations', 'as' => 'stations.', 'middleware' => ['permission:view.cities']], function () {
                Route::get('/', 'StationController@index')->name('index');
                Route::get('create', 'StationController@create')->name('create');
                Route::post('store', 'StationController@store')->name('store');
                Route::get('json', 'StationController@jsonData')->name('json');
                Route::group(['prefix' => '{station}'], function () {
                    Route::get('edit', 'StationController@edit')->name('edit');
                    Route::get('copy', 'StationController@copy')->name('copy');
                });
            });
        });

        //Schedules
        Route::group(['prefix' => 'schedules', 'as' => 'schedules.', 'middleware' => ['permission:view.schedules']], function () {
            Route::get('/', 'ScheduleController@index')->name('index');
            Route::get('getDriverId/{bus?}', 'ScheduleController@getDriverId')->name('getDriverId');
            Route::get('create', 'ScheduleController@create')->name('create');
            Route::post('store', 'ScheduleController@store')->name('store');
            Route::get('store', 'ScheduleController@store')->name('store');
            Route::group(['prefix' => '{schedule}'], function () {
                Route::get('edit', 'ScheduleController@edit')->name('edit');
                Route::get('copy/{copy?}', 'ScheduleController@edit')->name('copy');
                Route::get('delete', 'ScheduleController@delete')->name('delete');
            });
        });

        //Clients
        Route::group(['prefix' => 'clients', 'as' => 'clients.', 'middleware' => ['permission:view.clients']], function () {
            Route::get('/', 'ClientController@index')->name('index');
            Route::get('statics', 'ClientController@statics')->name('statics');
            Route::get('create', 'ClientController@create')->name('create');
            Route::get('change_status', 'ClientController@changeStatus')->name('change_status');
            Route::get('change_date_social', 'ClientController@changeStatusDuration')->name('change_date_social');
            Route::get('get_social_status', 'ClientController@getSelectSocialStatus')->name('get_social_status');
            Route::post('store', 'ClientController@store')->name('store');
            Route::get('show/print/template', 'ClientController@print_page_template_excel')->name('show.template');
            Route::post('import', 'ClientController@import')->name('import');
            Route::post('importcron', 'ClientController@importcron')->name('importcron');
            Route::any('export', 'ClientController@export')->name('export');
            Route::group(['prefix' => '{client}'], function () {
                Route::get('edit', 'ClientController@edit')->name('edit');
                Route::get('delete', 'ClientController@delete')->name('delete');
                Route::get('delete_client', 'ClientController@delete_client')->name('delete_client');
            });
        });

        //Calls
        Route::group(['prefix' => 'calls', 'as' => 'calls.'],
            function () {
                Route::get('/incomming/{number}', 'CallController@incomming')->name('incomming');
                Route::get('/hand_up/{number}', 'CallController@hand_up')->name('hand_up');
                Route::get('/missed/{number}', 'CallController@missed')->name('missed');
                Route::get('/out/{number?}', 'CallController@out')->name('out');
                Route::get('/outCall', 'CallController@outCall')->name('outCall');
            });

        //Tours
        Route::group(['prefix' => 'tours', 'as' => 'tours.', 'middleware' => ['permission:view.tours']], function () {
            Route::get('/', 'TourController@index')->name('index');

            Route::get('/get_cities', 'TourController@getCitiesFromCityId')->name('get_cities');
            Route::post('store', 'TourController@store')->name('store');
            Route::post('copyStore', 'TourController@copyStore')->name('copyStore');
            Route::post('store_rent', 'TourController@storeRent')->name('storeRent');
            Route::get('showPopup/{tour?}', 'TourController@showPopup')->name('showPopup');
            Route::get('showPopupRent/{tour?}', 'TourController@showPopupRent')->name('showPopupRent');
            Route::get('statistic', 'TourController@statistic')->name('statistic');
            Route::get('statistic/excel', 'TourController@statisticExcel')->name('statistic.excel');
            Route::group(['prefix' => '{tour}'], function () {

                Route::get('sendSms', 'TourController@sendSmsPopup')->name('sendSmsPopup');
                Route::post('sendSms', 'TourController@sendSms')->name('sendSms');
                Route::get('sendEgis', 'TourController@sendEgisPopup')->name('sendEgisPopup');
                Route::post('sendEgis', 'TourController@sendEgis')->name('sendEgis');

                Route::get('show', 'TourController@show')->name('show');
                Route::get('show/print', 'TourController@print_page')->name('show.print');
                Route::get('show/doc/print', 'TourController@print_document')->name('show.print.doc');
                Route::get('show/print/excel', 'TourController@print_page_excel')->name('show.excel');
                Route::get('show/print/reverse', 'TourController@print_page_reverse')->name('show.print.reverse');
                Route::get('show/print/reverse/excel', 'TourController@print_page_reverse_excel')->name('show.reverse.excel');
                Route::get('show/information', 'TourController@ordersMap')->name('show.information');
                Route::post('toPull', 'TourController@toPull')->name('toPull');
                Route::post('fromPull', 'TourController@fromPull')->name('fromPull');
                Route::get('delete', 'TourController@delete')->name('delete');
                Route::get('copy', 'TourController@copy')->name('copy');
                Route::get('show/print/template', 'TourController@print_page_template_excel')->name('show.template');
                Route::post('import', 'TourController@import')->name('import');
                Route::get('build_route', 'TourController@buildRoute')->name('build_route');
                Route::get('build_taxi_route', 'TourController@buildTaxiRoute')->name('build_taxi_route');
            });
        });

        //Packages
        Route::group(['prefix' => 'packages', 'as' => 'packages.', 'middleware' => ['permission:view.tours']], function () {
            Route::post('store', 'PackageController@store')->name('store');
            Route::get('showPopup/{package?}', 'PackageController@showPopup')->name('showPopup');
            Route::get('showPopupIndex/{tour_id}', 'PackageController@tourPackages')->name('tourPackages');
            Route::get('getRoutes/{date?}/{route?}', 'PackageController@getRoutes')->name('getRoutes');
            Route::get('IndexPackagesByDate/{date?}', 'PackageController@IndexPackagesByDate')->name('indexPackagesByDate');
        });

        //Rents
        Route::group(['prefix' => 'rents', 'as' => 'rents.', 'middleware' => ['permission:view.rents']], function () {
            Route::get('/', 'RentController@index')->name('index');
            Route::get('schedule', 'RentController@schedule')->name('schedule');
            Route::get('get_cities', 'RentController@getCitiesFromCityId')->name('get_cities');
            Route::get('getClientInfo', 'RentController@getClientInfo')->name('getClientInfo');
            Route::any('store', 'RentController@store')->name('store');
            Route::post('store_rent', 'RentController@storeRent')->name('storeRent');
            Route::get('showPopup/{tour?}', 'RentController@showPopup')->name('showPopup');
            Route::get('showPopupRent/{tour?}', 'RentController@showPopupRent')->name('showPopupRent');
            Route::get('get-agreement-tariffs', 'RentController@getAgreementTariffs')->name('getAgreementTariffs');
            Route::get('get-agreement-info', 'RentController@getAgreementInfo')->name('getAgreementInfo');
            Route::get('get-coordinates', 'RentController@getCoordinates')->name('getCoordinates');
            Route::group(['prefix' => '{tour}'], function () {

                Route::get('sendSms', 'RentController@sendSmsPopup')->name('sendSmsPopup');
                Route::post('sendSms', 'RentController@sendSms')->name('sendSms');

                Route::get('show', 'RentController@show')->name('show');
                Route::get('show/print', 'RentController@print_page')->name('show.print');
                Route::post('toPull', 'RentController@toPull')->name('toPull');
                Route::post('fromPull', 'RentController@fromPull')->name('fromPull');
                Route::get('delete', 'RentController@delete')->name('delete');
            });
        });

        //Orders
        Route::group(['prefix' => 'orders', 'as' => 'orders.', 'middleware' => ['permission:view.orders']], function () {
            Route::get('/', 'OrderController@index')->name('index');
            Route::get('create/{order?}', 'OrderController@create')->name('create');
            Route::get('getClientInfo', 'OrderController@getClientInfo')->name('getClientInfo');
            Route::get('toTour/{tour}/{order?}', 'OrderController@toTour')->name('toTour');
            Route::get('toTours/{order?}', 'OrderController@toTours')->name('toTours');
            Route::post('store', 'OrderController@store')->name('store');
            Route::post('import', 'OrderController@import')->name('import');
            Route::get('delete', 'OrderController@delete')->name('delete');
            Route::get('export', 'OrderController@export')->name('export');
            Route::get('is_call', 'OrderController@isCall')->name('is_call');
            Route::get('children', 'OrderController@children')->name('children');
            Route::get('select_station_to_id', 'OrderController@StationToId')->name('select_station_to_id');
            Route::get('change_from_time', 'OrderController@ChangeFromTime')->name('change_from_time');
            Route::get('change_price', 'OrderController@ChangePrice')->name('change_price');
            Route::post('save_data_order_places', 'OrderController@SaveDataOrderPlaces')->name('save_data_order_places');
            Route::post('save_data_order', 'OrderController@SaveDataOrder')->name('save_data_order');
            Route::get('check_stations', 'OrderController@checkStations')->name('check_stations');
            Route::get('printReport', 'OrderController@reportPopup')->name('printReport');
            Route::get('exportReport', 'OrderController@exportReport')->name('exportReport');
            Route::group(['prefix' => '{order}'], function () {
                Route::get('edit', 'OrderController@edit')->name('edit');
                Route::get('print', 'OrderController@printOrder')->name('print');
                Route::get('pdf', 'OrderController@pdf')->name('pdf');
                Route::get('generate_pdf', 'OrderController@generatePdf')->name('generate_pdf');
                Route::get('generate_pdf_op', 'OrderController@generatePdfOP')->name('generate_pdf_op');
                Route::get('pay', 'OrderController@pay')->name('pay');
                Route::get('cancel', 'OrderController@cancel')->name('cancel');
                Route::get('restore', 'OrderController@restore')->name('restore');
                Route::get('delete_order', 'OrderController@delete_order')->name('delete_order');
                Route::get('get_check', 'OrderController@getEkamCheck')->name('get_check');
            });
        });

        //Pull
        Route::group(['prefix' => 'pulls', 'as' => 'pulls.', 'middleware' => ['permission:view.orders']], function () {
            Route::get('tours', 'PullController@tours')->name('tours');
            Route::get('orders', 'PullController@orders')->name('orders');
            Route::get('count', 'PullController@count')->name('count');
        });


        Route::group(['prefix' => 'bus_type', 'as' => 'bus_type.'], function () {
            Route::get('index', 'BusTypeController@index')->name('index');
            Route::post('store', 'BusTypeController@store')->name('store');
            Route::get('create', 'BusTypeController@create')->name('create');
            Route::group(['prefix' => '{busType}'], function () {
                Route::get('edit', 'BusTypeController@edit')->name('edit');
            });
        });

        Route::group(['prefix' => 'incidents', 'as' => 'incidents.'], function () {
            Route::get('/', 'IncidentController@index')->name('index');
            Route::get('create', 'IncidentController@create')->name('create');
            Route::post('store', 'IncidentController@store')->name('store');
            Route::group(['prefix' => '{incident}'], function () {
                Route::get('edit', 'IncidentController@edit')->name('edit');
            });
        });

        Route::group(['middleware' => ['permission:view.repair']], function () {

            Route::group(['prefix' => 'repair_orders', 'as' => 'repair_orders.', 'namespace' => 'Repair'], function () {

                Route::group(['middleware' => ['permission:view.repair.order']], function () {
                    Route::get('get-departments-cars-view', 'RepairController@getDepartmentsCarsView')->name('getDepartmentsCarsView');
                    Route::get('get-car-cards-view', 'RepairController@getCarCardsView')->name('getCarCardsView');
                    Route::get('{repair_order}/complete', 'RepairController@complete')->name('complete');
                    Route::post('{repair_order}/finish', 'RepairController@finish')->name('finish');
                });

                Route::group(['prefix' => '/{repair_order}/diagnostic_cards', 'as' => 'diagnostic_cards.', 'middleware' => ['permission:view.repair.card']], function () {
                    Route::get('get-card-content', 'DiagnosticCardController@getCardContent')->name('getCardContent');
                });

                Route::group(['prefix' => '/{repair_order}/spare_parts', 'as' => 'spare_parts.', 'middleware' => ['permission:view.repair.parts']], function () {
                    Route::get('get-content', 'SparePartController@getContent')->name('content');
                    Route::post('store-mass', 'SparePartController@storeMass')->name('storeMass');
                });
            });

            Route::resource('repair_orders.spare_parts', 'Repair\SparePartController',
                ['only' => ['index', 'store', 'destroy'], 'middleware' => ['permission:view.repair.parts']]);

            Route::resource('repair_orders.diagnostic_cards', 'Repair\DiagnosticCardController',
                ['middleware' => ['permission:view.repair.card']]);

            Route::resource('repair_orders.order_outfits', 'Repair\OrderOutfitController',
                ['middleware' => ['permission:view.repair.orderoutfit']]);

            Route::resource('repair_orders', 'Repair\RepairController', ['only' => ['index']]);
            Route::resource('repair_orders', 'Repair\RepairController',
                ['except' => ['index'], 'middleware' => ['permission:view.repair.order']]);
        });

        // Interface settings
        Route::group(
            [
                'prefix' => 'settings/interface_settings',
                'as' => 'settings.interface_settings.',
                'middleware' => ['permission:view.admininterfacesettings']
            ], function () {
            Route::get('edit', 'InterfaceSettingsController@edit')->name('edit');
            Route::post('store', 'InterfaceSettingsController@store')->name('store');
            Route::post('change_background_image', 'ChangeImageController@change_background_image')->name('change_background_image');
        });

        //Garage Area
        Route::group(['prefix' => 'garage', 'as' => 'garage.'], function () {
            Route::get('cars-index', 'GarageCarController@index')->name('cars.index');
            Route::get('{bus}/cars-take', 'GarageCarController@takeCar')->name('cars.take');
            Route::post('store', 'GarageCarController@store')->name('cars.store');
            Route::post('put', 'GarageCarController@put')->name('cars.put');
            Route::get('car-taken', 'GarageCarController@carTaken')->name('cars.taken');
        });

        //Settings
        Route::group(['prefix' => 'settings', 'as' => 'settings.', 'middleware' => ['permission:view.settings']], function () {
            Route::get('edit', 'SettingController@edit')->name('edit');
            Route::post('store', 'SettingController@store')->name('store');
            Route::get('setToursFieldsPopup', 'SettingController@setToursFieldsPopup')->name('setToursFieldsPopup');
            Route::post('setToursFieldsPopup', 'SettingController@setToursFields')->name('setToursFieldsPopup');

            //Config
            Route::group(['prefix' => 'smsconfig', 'as' => 'smsconfig.'], function () {
                Route::get('edit', 'SmsConfigController@edit')->name('edit');
                Route::post('store', 'SmsConfigController@store')->name('store');
                Route::get('ajaxup', 'SmsConfigController@moveup')->name('ajaxup');
                Route::get('ajaxdown', 'SmsConfigController@movedown')->name('ajaxdown');
            });

            //Driver app
            Route::group(['prefix' => 'driverapp', 'as' => 'driverapp.'], function () {
                Route::get('edit', 'DriverAppController@edit')->name('edit');
                Route::post('store', 'DriverAppController@store')->name('store');
                Route::post('image-upload', 'DriverAppController@imageUploadPost')->name('upload');
            });

            //Mobile app
            Route::group(['prefix' => 'mobile_app', 'as' => 'mobile_app.'], function () {
                Route::get('edit', 'SettingController@mobileEdit')->name('edit');
                Route::post('store', 'SettingController@mobileStore')->name('store');
            });

            //Statuses
            Route::group(['prefix' => 'statuses', 'as' => 'statuses.'], function () {
                Route::get('/', 'StatusController@index')->name('index');
                Route::get('create', 'StatusController@create')->name('create');
                Route::post('store', 'StatusController@store')->name('store');
                Route::group(['prefix' => '{status}'], function () {
                    Route::get('edit', 'StatusController@edit')->name('edit');
                });
            });
            //Additional services
            Route::group(['prefix' => 'add_services', 'as' => 'add_services.'], function () {
                Route::get('/', 'AddServiceController@index')->name('index');
                Route::get('create', 'AddServiceController@create')->name('create');
                Route::post('store', 'AddServiceController@store')->name('store');
                Route::group(['prefix' => '{service}'], function () {
                    Route::get('edit', 'AddServiceController@edit')->name('edit');
                });
            });
            //Sms
            Route::group(['prefix' => 'sms', 'as' => 'sms.'], function () {
                Route::get('/test', 'SmsController@index')->name('index');
            });
            //Sales
            Route::group(['prefix' => 'sales', 'as' => 'sales.'], function () {
                Route::get('/', 'SaleController@index')->name('index');
                Route::get('create', 'SaleController@create')->name('create');
                Route::post('store', 'SaleController@store')->name('store');
                Route::group(['prefix' => '{sale}'], function () {
                    Route::get('edit', 'SaleController@edit')->name('edit');
                });
            });
            //Coupons
            Route::group(['prefix' => 'coupons', 'as' => 'coupons.'], function () {
                Route::get('/', 'CouponController@index')->name('index');
                Route::get('create', 'CouponController@create')->name('create');
                Route::post('store', 'CouponController@store')->name('store');
                Route::group(['prefix' => '{coupon}'], function () {
                    Route::get('edit', 'CouponController@edit')->name('edit');
                });
            });
            // Clients interface settings
            Route::group(
                ['prefix' => 'clients_interface_settings', 'as' => 'clients_interface_settings.'], function () {
                Route::get('image-upload', 'ClientsInterfaceSettingController@imageUpload')->name('edit');
                Route::post('image-upload', 'ClientsInterfaceSettingController@imageUploadPost')->name('upload');
                Route::post('image-delete', 'ClientsInterfaceSettingController@imageDelete')->name('delete');
                Route::group(['prefix' => 'frame', 'as' => 'frame.'], function () {
                    Route::post('save', 'ClientsInterfaceSettingController@saveFrame')->name('save');
                });
            });

            Route::group(['prefix' => 'sms', 'as' => 'coupons.'], function () {
                Route::get('/', 'CouponController@index')->name('index');
            });

            //Roles
            Route::group(['prefix' => 'roles', 'as' => 'roles.'], function () {
                Route::get('/', 'RoleController@index')->name('index');
                Route::get('create', 'RoleController@create')->name('create');
                Route::post('store', 'RoleController@store')->name('store');
                Route::group(['prefix' => '{role}'], function () {

                    Route::get('edit', 'RoleController@edit')->name('edit');
                    Route::get('delete', 'RoleController@delete')->name('delete');
                });
            });

            //Notifications
            Route::group(['prefix' => 'notifications', 'as' => 'notifications.', 'middleware' => ['permission:view.notification.types']], function () {
                Route::get('/', 'NotificationTypeController@index')->name('index');
                Route::get('create', 'NotificationTypeController@create')->name('create');
                Route::post('store', 'NotificationTypeController@store')->name('store');
                Route::group(['prefix' => '{notification}'], function () {

                    Route::get('edit', 'NotificationTypeController@edit')->name('edit');
                    Route::get('delete', 'NotificationTypeController@delete')->name('delete');
                });
            });

            Route::group(['prefix' => 'car_settings', 'as' => 'car_settings.'], function () {
                Route::get('/', 'CarSettingsController@index')->name('index');

                Route::resource('car_colors', 'CarColorController');
                Route::resource('customer_persons', 'CustomerPersonalityController');
                Route::resource('customer_companies', 'CustomerCompanyController');
                Route::resource('customer_departments', 'CustomerDepartmentController');
            });
            //Wishes type
            Route::group(['prefix' => 'wishes', 'as' => 'wishes.', 'middleware' => ['permission:view.wishes.types']], function () {
                Route::get('/', 'WishesTypeController@index')->name('index');
                Route::get('create', 'WishesTypeController@create')->name('create');
                Route::post('store', 'WishesTypeController@store')->name('store');
                Route::group(['prefix' => '{wishesType}'], function () {
                    Route::get('edit', 'WishesTypeController@edit')->name('edit');
                    Route::get('delete', 'WishesTypeController@delete')->name('delete');
                });
            });

            //Exploitation
            Route::group(['prefix' => 'exploitation', 'as' => 'exploitation.'], function () {
                Route::get('/', 'ExploitationController@index')->name('index');
                Route::get('/review-templates', 'ExploitationController@reviewTemplates')->name('reviewTemplates');

                Route::group(['prefix' => 'incident', 'as' => 'incident.'], function () {
                    Route::get('', 'IncidentTemplateController@index')->name('index');
                    Route::get('create', 'IncidentTemplateController@create')->name('create');
                    Route::post('store', 'IncidentTemplateController@store')->name('store');
                    Route::group(['prefix' => '{incident_template}'], function () {
                        Route::get('edit', 'IncidentTemplateController@edit')->name('edit');
                    });

                });
                Route::group(['prefix' => 'review', 'as' => 'review.'], function () {

                    Route::get('', 'ReviewActTemplateController@index')->name('index');
                    Route::get('create', 'ReviewActTemplateController@create')->name('create');
                    Route::post('store', 'ReviewActTemplateController@store')->name('store');
                    Route::group(['prefix' => '{reviewActTemplate}'], function () {
                        Route::get('edit', 'ReviewActTemplateController@edit')->name('edit');

                        Route::group(['prefix' => 'items', 'as' => 'items.'], function () {
                            Route::get('/', 'ReviewActTemplateItemController@index')->name('index');
                            Route::get('create', 'ReviewActTemplateItemController@create')->name('create');
                            Route::post('store', 'ReviewActTemplateItemController@store')->name('store');
                            Route::group(['prefix' => '{reviewActTemplateItem}'], function () {
                                Route::get('edit', 'ReviewActTemplateItemController@edit')->name('edit');
                            });
                        });
                    });
                });
                Route::group(['prefix' => 'diagnostic', 'as' => 'diagnostic.'], function () {
                    Route::get('', 'DiagnosticCardTemplateController@index')->name('index');

                    Route::get('create', 'DiagnosticCardTemplateController@create')->name('create');
                    Route::post('store', 'DiagnosticCardTemplateController@store')->name('store');
                    Route::group(['prefix' => '{diagnosticCardTemplate}'], function () {
                        Route::get('edit', 'DiagnosticCardTemplateController@edit')->name('edit');
                        Route::group(['prefix' => 'items', 'as' => 'items.'], function () {
                            Route::get('/', 'DiagnosticCardTemplateItemController@index')->name('index');
                            Route::get('create', 'DiagnosticCardTemplateItemController@create')->name('create');
                            Route::post('store', 'DiagnosticCardTemplateItemController@store')->name('store');
                            Route::group(['prefix' => '{reviewActTemplate}'], function () {
                                Route::get('free', 'DiagnosticCardTemplateItemController@free')->name('free');
                            });
                        });
                    });

                });

                Route::resource('breakages', 'CarBreakageController');
                Route::group(['prefix' => 'repair_cards/{repair_card}/items', 'as' => 'repair_cards.items.'], function () {
                    Route::get('create', 'RepairCardTemplateController@createItem')->name('create');
                    Route::get('{item}/edit', 'RepairCardTemplateController@editItem')->name('edit');
                });


                Route::resource('repair_cards', 'RepairCardTemplateController');

                Route::group(['prefix' => 'repair_card_types/{repair_card_type}/items', 'as' => 'repair_card_types.items.'], function () {
                    Route::get('create', 'RepairCardTypeController@createItem')->name('create');
                    Route::post('store', 'RepairCardTypeController@storeItem')->name('store');
                    Route::get('{repair_card_template}/delete', 'RepairCardTypeController@deleteItem')->name('delete');
                });

                Route::resource('repair_card_types', 'RepairCardTypeController');

                Route::resource('spare_parts.items', 'SparePartItemController');
                Route::resource('spare_parts', 'SparePartController');


            });

            //Amenities
            Route::group(['prefix' => 'amenities', 'as' => 'amenities.'], function () {
                Route::get('/', 'AmenityController@index')->name('index');
                Route::get('create', 'AmenityController@create')->name('create');

                Route::post('store', 'AmenityController@store')->name('store');
                Route::group(['prefix' => '{amenity}'], function () {
                    Route::get('edit', 'AmenityController@edit')->name('edit');
                    Route::get('delete', 'AmenityController@delete')->name('delete');
                });
            });
        });

        //Sms
        Route::group(['prefix' => 'sms', 'as' => 'sms.'], function () {
            Route::get('/individual_popup/{order}', 'SmsController@individualPopup')->name('individual_popup');
            Route::post('/send', 'SmsController@send')->name('send');
        });

        //Monitoring
        Route::group(['prefix' => 'monitoring', 'as' => 'monitoring.', 'middleware' => ['permission:view.monitoring']], function () {
            Route::get('/', 'MonitoringController@index')->name('index');
            Route::get('/driverwaypoints', 'MonitoringController@driverWaypoints');
            Route::get('/driverswaypoints', 'MonitoringController@driversWaypoints');
            Route::get('/routewaypoints', 'MonitoringController@routeWaypoints');
            Route::get('/tempdrivers', 'MonitoringController@postGeoAndSpeed');
            Route::post('/sethighspeed', 'MonitoringController@highSpeed');
            Route::get('/getbusspeed', 'MonitoringController@busSpeed');
        });

        //Cron settings
        Route::group(['prefix' => 'cron', 'as' => 'cron.', 'middleware' => ['permission:view.cron']], function () {
            Route::get('/', 'CronController@index')->name('index');
            Route::group(['prefix' => '{cron}'], function () {
                Route::get('delete', 'CronController@delete')->name('delete');
            });
        });
        Route::group(['prefix' => 'dashboards', 'as' => 'dashboards.', 'namespace' => 'Dashboard'], function () {
            Route::group(['prefix' => 'buses', 'as' => 'buses.'], function () {
                Route::any('/', 'BusController@index')->name('index');
                Route::post('filter', 'BusController@filter')->name('filter');
                Route::post('{bus}/update-one', 'BusController@updateOne')->name('updateOne');
            });
        });

        Route::group(['prefix' => 'operational_tasks', 'as' => 'operational_tasks.', 'middleware' => ['operationalTask']], function () {
            Route::get('/create', 'OperationalTaskController@create')->name('create');
            Route::post('/store', 'OperationalTaskController@store')->name('store');
            Route::get('/', 'OperationalTaskController@index')->name('index');
            Route::get('/{taskId}', 'OperationalTaskController@detail')->name('show');
            Route::put('/{taskId}/edit', 'OperationalTaskController@edit')->name('edit');
        });
    });

});



