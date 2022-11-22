<?php

Route::get('/', 'HomeController@index')->name('home');
Route::get('/test', 'HomeController@test')->name('test');
Route::post('stations', 'HomeController@stations')->name('stations');
Route::get('cities', 'HomeController@cities')->name('cities');
Route::get('city_stations', 'HomeController@cityStations')->name('cityStations');
Route::get('get_route', 'HomeController@getRoute')->name('get_route');
Route::get('get-rand-img', 'HomeController@getImages');

//Schedule
Route::group(['prefix' => 'schedules', 'as' => 'schedules.'], function () {
	Route::get('/', 'ScheduleController@index')->name('index');
	Route::get('/get_tour_dates', 'ScheduleController@getTourDates')->name('getTourDates');
	Route::post('getBus/{tour}', 'ScheduleController@getBus')->name('getBus');
	Route::post('storePlaces/{tour}', 'ScheduleController@storePlaces')->name('storePlaces');
	Route::get('/embedded', 'ScheduleController@embeddedForm');
});

//Order
Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
	Route::get('/', 'OrderController@index')->name('index');
	Route::post('store', 'OrderController@store')->name('store');
	Route::get('coupon', 'OrderController@coupon')->name('coupon');
	Route::get('children', 'OrderController@children')->name('children');
	Route::get('confirm', 'OrderController@confirm')->name('confirm');
	Route::post('confirm', 'OrderController@doConfirm')->name('do-confirm');
	Route::get('result', 'OrderController@result')->name('result')->middleware('client');
	Route::get('print/{order?}', 'OrderController@printOrder')->name('print')->middleware('client');
	Route::get('printing/{order}', 'OrderController@printing')->name('printing')->middleware('client');

	Route::get('get_check/{order}', 'OrderController@getEkamCheck')->name('get_check');

	Route::get('pay', 'OrderController@pay')->name('pay')->middleware('client');
	Route::post('pay/rnkb', 'PayController@pay');

	Route::get('/pay/on_success_rnkb', 'PayController@onSuccessRNKB');
	Route::get('/pay/on_fail_rnkb', 'PayController@onFailRNKB');

	Route::get('/pay/on_fail_alfabank', 'PayController@onFailAlfabank');
	Route::get('/pay/on_success_alfabank', 'PayController@onSuccessAlfabank');

	Route::get('/pay/on_fail_webpay', 'PayController@onFailWebpay');
	Route::get('/pay/on_success_webpay', 'PayController@onSuccessWebpay');
	
	Route::get('/pay/on_fail_sberbank', 'PayController@onFailSberbank');
	Route::get('/pay/on_success_sberbank', 'PayController@onSuccessSberbank');

	Route::get('pay/{order}', 'OrderController@payOrder')->name('payOrder')->middleware('client');
	Route::post('notice_pay', 'OrderController@noticePayOrder')->name('notice_pay');
    Route::get('partial_pay', 'OrderController@pay')->name('partial_pay')->defaults('partial', true)->middleware('client');
    Route::get('international', 'OrderController@confirmInternational')->name('international');
    Route::get('set_stations', 'OrderController@setStations')->name('set_stations');
    Route::post('international', 'OrderController@doConfirmInternational')->name('international');
    Route::post('ind_transfer', 'OrderController@indTransfer')->name('ind_transfer');
});
Route::get('ticket/{slug}', 'OrderController@ticket')->name('ticket');
Route::get('pdf/{slug}', 'OrderController@pdf')->name('order-pdf');
Route::get('pay/{slug}', 'PayController@paymentPage')->name('order-pay');

//Auth
Route::group(['as' => 'auth.', 'prefix' => 'auth'], function () {
	Route::post('login', 'AuthController@doLogin')->name('do-login');
    Route::post('forget', 'AuthController@forget')->name('forget');
	Route::post('register', 'AuthController@doRegister')->name('do-register');
	Route::get('logout', 'AuthController@logout')->name('logout');
	Route::get('confirm', 'AuthController@confirm')->name('confirm');
	Route::post('confirm', 'AuthController@doConfirm')->name('do-confirm');
    //Route::get('send_password', 'SmsController@password')->name('password');
});

//Profile
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'middleware' => 'client'], function () {
	Route::group(['as' => 'settings.'], function () {
		Route::get('/', 'ProfileController@settings')->name('index');
		Route::post('/', 'ProfileController@settingsUpdate')->name('store');
		Route::post('update/email', 'ProfileController@updateEmail');
	});
	Route::group(['as' => 'tickets.', 'prefix' => 'tickets'], function () {
		Route::get('/', 'ProfileController@tickets')->name('index');
		Route::get('show/{order}', 'ProfileController@showOrder')->name('showOrder');
		Route::post('/', 'ProfileController@ticketsCancel')->name('cancel');
	});
	Route::get('reviews', 'ProfileController@tickets')->name('reviews');
	Route::post('create-reviews', 'ProfileController@createReview');
	Route::get('generate-pdf/{id}','OrderPrintController@printPDF')->name('generatePDF');
	Route::get('generate-pdf-to-email/{id}','OrderToEmailController@sendPDF')->name('generatePDFToEmail');
});

//Pages
Route::get('{slug}', 'PageController@index')->name('page');

Route::get('locale/{locale}', function ($locale) {
    if (in_array($locale, \Config::get('app.locales'))) {   # Проверяем, что у пользователя выбран доступный язык
        Session::put('locale', $locale);                    # И устанавливаем его в сессии под именем locale
    }
    return redirect()->back();                              # Редиректим его <s>взад</s> на ту же страницу
});
