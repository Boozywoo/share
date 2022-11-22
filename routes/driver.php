<?php

use Illuminate\Support\Facades\App;

Route::get('/', function () {
    return view('driver.auth.login');
});


Route::get('/login', ['uses' => 'Auth\LoginController@showLoginForm']);
Route::post('/login', ['as' => 'login', 'uses' => 'Auth\LoginController@login']);
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');


Auth::routes();

Route::get('/tours', function () {
    return redirect('driver/tours/getToursToday');
});

Route::get('/tourPackages/{tour_id}', 'PackageController@tourPackages')->name('tourPackages');
Route::post('/setStatusPackage/{package_id}/{status}', 'PackageController@setStatus')->name('setStatusPackage');

Route::get('/tours/passengers/{id}/get_id', 'PassengerController@getId')->where('id', '[0-9-]+');

Route::get('/tours/getToursToday', 'ToursController@getToursToday')->name('tourToday');
Route::get('/tours/getToursTomorrow', 'ToursController@getToursTomorrow');
Route::get('/tours/getToursOnWeek', 'ToursController@getToursOnWeek');
Route::get('/tours/getToursOnMonth', 'ToursController@getToursOnMonth');

Route::post('/tours/display_cities', 'SettingsController@displayCities');
Route::post('/tours/display_streets', 'SettingsController@displayStreets');
Route::post('/tours/display_stations', 'SettingsController@displayStations');
Route::post('/tours/display_utc', 'SettingsController@displayUTC');
Route::post('/tours/display_button_finished', 'SettingsController@displayButtonFinished');
Route::post('/tours/change_code', 'SettingsController@changeCode');

Route::get('/tours/passengers/{tour}/landing', 'PassengerController@landing')->name('landing');
Route::get('/tours/passengers/{tour}/way', 'PassengerController@way')->name('way');
Route::post('/tours/passengers/{tour}/completed', 'PassengerController@completed');
Route::post('/tours/passengers/{tour}/add_passenger', 'OrderController@add')->name('landing.add_passenger');
Route::post('/tours/taxiorder/{tour}/add', 'OrderController@addTaxiOrder');
Route::get('/tours/build_route/{tour}', 'ToursController@buildRoute')->name('buildRoute');
Route::get('/tours/navi_link/{tour}', 'ToursController@navigatorLink')->name('naviLink');
Route::get('/tours/calc_time/{tour}', 'ToursController@calcTime')->name('calcTime');
Route::post('/tours/bbv/auth', 'ToursController@bbvAuth')->name('bbv.auth');
Route::post('/tours/bbv/receipt', 'ToursController@bbvReceipt');
Route::post('/tours/bbv/calc_cash', 'ToursController@bbvCalcCash');
Route::post('/tours/bbv/close', 'ToursController@bbvClose');

Route::get('/tours/passengers/{tour}/add', 'PassengerController@add')->name('add');
Route::get('/tours/passengers/{tour}/open/{station}', 'PassengerController@open')->name('open');

Route::get('/tours/passengers/{tour}/generate_pdf', 'PassengerController@generatePdf')->name('generate_pdf');

Route::post('/tours/passengers/{tour}/switch_appearance_all', 'PassengerController@switchAppearanceAll');
Route::post('/tours/passengers/{tour}/switch_appearance_on_ticket', 'PassengerController@switchAppearanceOnTicket');
Route::post('/tours/passengers/{tour}/switch_appearance', 'PassengerController@switchAppearance');
Route::post('/tours/passengers/{tour}/switch_appearance_on_station', 'PassengerController@switchAppearanceOnStation');
Route::post('/tours/passengers/{tour}/fill_order', 'PassengerController@fillOrder');
Route::post('/tours/passengers/{tour}/set_presence', 'PassengerController@setPresence');
Route::post('/tours/passengers/{tour}/set_finished', 'PassengerController@setFinished');
Route::post('/tours/passengers/{tour}/set_finished_all', 'PassengerController@setFinishedAll');
Route::post('/tours/passengers/{tour}/unset_finished_all', 'PassengerController@unsetFinishedAll');
Route::post('/tours/passengers/{tour}/unset_finished', 'PassengerController@unsetFinished');
Route::post('/tours/passengers/{tour}/switch_pay', 'PassengerController@switchPay');
Route::post('/tours/passengers/{tour}/switch_call', 'PassengerController@switchCall');
Route::post('/tours/passengers/{tour}/cancel_order', 'PassengerController@cancelOrder');

Route::get('/tours/passengers/{tour}/get_check', 'PassengerController@getEkamCheck')->name('get_check');


Route::get('/routewaypoints', 'PassengerController@getRouteWaypoints')->name('getRouteWaypoints');

