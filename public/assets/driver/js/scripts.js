/*!*******************************************!*\
  !*** ./resources/assets/driver/js/main.js ***!
  \*******************************************/
let informHide = false;
let informOrderHide = false;
let valApp = 0, valNoApp = 0, sId;
var Cookie = "IsShow";
var is_show = $.cookie(Cookie) || 1;

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function () {
    if ($.cookie(Cookie) == 1) {
        $(".client-inform").show();
    } else {
        $(".client-inform").hide();
    }

    maskPhone();

    $('#popup_packages_of_tour').on('show.bs.modal', function (e) {
        let $button = $(e.relatedTarget)
        let url = $button.data('url')
        $.get(url, (response) => {
            $(this).find('.modal-content').html(response.html)
        });
    });

    $("#from").change(function () {
        var option = $("#from > option:selected");
        var city_from = option.attr("city");
        var order_from = +option.attr("order");
        $('#to').attr("disabled", null);

        $("#to > option").each(function () {
            $(this).attr("hidden", null);

            var order = +$(this).attr("order");
            var city = $(this).attr("city");
            if (city_from == city || order <= order_from) {
                $(this).attr("hidden", "");
            }
        });
    });

    $('#count_places').change(function () {
        $('#price').val($('#price').attr('price') * $("#count_places").val());
    })

    $("#filter_type").change(function () {
        var option = $("#filter_type > option:selected");
        var filter = option.attr("value");

        window.location.search = "filterType=" + filter;
    });

    $('#country').change(function () {
        maskPhone();
    });
});

$(document).on('click', '.js_inform_show', function () {
    informHide = !informHide;
    if (informHide) {
        is_show = 0;
        $.cookie(Cookie, is_show);
        $(".client-inform").hide();
    } else {
        is_show = 1;
        $.cookie(Cookie, is_show);
        $(".client-inform").show();
    }
});

$(document).on('click', '.js_show_information', function () {
    informOrderHide = !informOrderHide;
    id = $(this).attr('id');

    if (informOrderHide) {
        $('.' + id).hide();
    } else {
        $('.' + id).show();
    }
});

function switchCall(orderId, tourId, value) {
    $('.js_spinner-overlay').show();
    $('.background-spinner').show();
    $.post("/driver/tours/passengers/" + $(this).attr("tour") + "/switch_call", {
        orderId: orderId,
        tour_id: tourId,
        isChecked: value
    })
        .done(function (data) {
            $("#head" + orderId).load(" #head" + orderId);
            $('.js_spinner-overlay').hide();
            $('.background-spinner').hide();
        });
}

function switchPay(orderId, tourId, value) {
    $('.js_spinner-overlay').show();
    $('.background-spinner').show();
    $.post("/driver/tours/passengers/" + $(this).attr("tour") + "/switch_pay",
        {orderId: orderId, isChecked: value, tour_id: tourId})
        .done(function (data) {
            $("#head" + orderId).load(" #head" + orderId);
            $('.js_spinner-overlay').hide();
            $('.background-spinner').hide();
        });
}

function alertForD(id) {
    alert(`Вы можете нажать на рейс только за ${id} часа до его начала!`);
}

function switchAppearanceOnStation(ordersId, tourId, isAll, stationId) {
    $('.js_spinner-overlay').show();
    $('.background-spinner').show();
    $.post("/driver/tours/passengers/" + tourId + "/switch_appearance_on_station",
        {orders_id: ordersId, tour_id: tourId, is_all: isAll, station_id: stationId})
        .done(function (data) {
            $('.js_spinner-overlay').hide();
            $('.background-spinner').hide();
            location.reload();
        });
}

function fillOrder(ordersId, tourId) {
    $('.js_spinner-overlay').show();
    $('.background-spinner').show();
    $.post("/driver/tours/passengers/" + tourId + "/fill_order",
        {order_id: ordersId})
        .done(function (data) {
            $('.js_spinner-overlay').hide();
            $('.background-spinner').hide();
            location.reload();
        });
}

function orderSetPresence(ordersId, tourId) {
    $('.js_spinner-overlay').show();
    $('.background-spinner').show();
    $.post("/driver/tours/passengers/" + tourId + "/set_presence",
        {order_id: ordersId})
        .done(function (data) {
            $('.js_spinner-overlay').hide();
            $('.background-spinner').hide();
            location.reload();
        });
}

function openCancel(order_id) {
    $('#cancel_order').val(order_id);
}

function switchAppearance(orderId, tourId, mainOrderId, stationId) {
    $('.js_spinner-overlay').show();
    $('.background-spinner').show();

    $.post("/driver/tours/passengers/" + tourId + "/switch_appearance", {orderId: orderId, tour_id: tourId})
        .done(function (data) {
            $.get(location.href).then(function (page) {
                $("." + mainOrderId).html($(page).find("." + mainOrderId).html());
                if (data == 1) {
                    valApp = (($(".app." + stationId).val() ? parseInt($(".app." + stationId).val()) : null) ?? valApp) + 1;

                    if (($(".noapp." + stationId).val() ? parseInt($(".noapp." + stationId).val()) : valNoApp) > 0) {
                        valNoApp = (($(".noapp." + stationId).val() ? parseInt($(".noapp." + stationId).val()) : null) ?? valNoApp) - 1;
                    }
                    sId = stationId ?? sId;

                    $(".noapp." + sId).val(valNoApp);
                    $(".app." + sId).val(valApp);
                } else if (data == 0) {

                    if (($(".app." + stationId).val() ? parseInt($(".app." + stationId).val()) : valApp) > 0) {
                        valApp = (($(".app." + stationId).val() ? parseInt($(".app." + stationId).val()) : null) ?? valApp) - 1;
                    }

                    valNoApp = (($(".noapp." + stationId).val() ? parseInt($(".noapp." + stationId).val()) : null) ?? valNoApp) + 1;
                    sId = stationId ?? sId;

                    $(".noapp." + sId).val(valNoApp);
                    $(".app." + sId).val(valApp);
                } else {
                    if (($(".noapp." + stationId).val() ? parseInt($(".noapp." + stationId).val()) : valNoApp) > 0) {
                        valNoApp = (($(".noapp." + stationId).val() ? parseInt($(".noapp." + stationId).val()) : null) ?? valNoApp) - 1;
                    }
                    sId = stationId ?? sId;
                    $(".noapp." + sId).val(valNoApp);
                }
                $('.js_spinner-overlay').hide();
                $('.background-spinner').hide();
                // $(".noapp").html($(page).find(".noapp").html());
                // $(".app").html($(page).find(".app").html());
            })
        });
}

function switchAppearanceAll(tourId, is_all) {
    $('.js_spinner-overlay').show();
    $('.background-spinner').show();
    $.post("/driver/tours/passengers/" + tourId + "/switch_appearance_all", {tour_id: tourId, is_all: is_all})
        .done(function (data) {
            $('.js_spinner-overlay').hide();
            $('.background-spinner').hide();
            location.reload();
        });
}

function cancelOrder(orderId, tourId) {
    $('.js_spinner-overlay').show();
    $('.background-spinner').show();
    $.post("/driver/tours/passengers/" + tourId + "/cancel_order", {orderId: orderId, tour_id: tourId})
        .done(function (data) {
            $('.js_spinner-overlay').hide();
            $('.background-spinner').hide();
            location.reload();
        });
}

function completed(tourId) {
    $('.js_spinner-overlay').show();
    $('.background-spinner').show();
    $.post("/driver/tours/passengers/" + tourId + "/completed", {tour_id: tourId})
        .done(function (data) {
            $('.js_spinner-overlay').hide();
            $('.background-spinner').hide();
            window.location.href = '/driver/tours/';
        });
}

function setFinished(tourId, orderId) {
    $('.js_spinner-overlay').show();
    $('.background-spinner').show();
    $.post("/driver/tours/passengers/" + tourId + "/set_finished", {tour_id: tourId, order_id: orderId})
        .done(function (data) {
            $('.js_spinner-overlay').hide();
            $('.background-spinner').hide();
            location.reload();
        });
}

function unsetFinished(tourId, orderId) {
    $('.js_spinner-overlay').show();
    $('.background-spinner').show();
    $.post("/driver/tours/passengers/" + tourId + "/unset_finished", {tour_id: tourId, order_id: orderId})
        .done(function (data) {
            $('.js_spinner-overlay').hide();
            $('.background-spinner').hide();
            location.reload();
        });
}

function setFinishedAll(ordersId, tourId) {
    $('.js_spinner-overlay').show();
    $('.background-spinner').show();
    $.post("/driver/tours/passengers/" + tourId + "/set_finished_all", {orders_id: ordersId, tour_id: tourId})
        .done(function (data) {
            $('.js_spinner-overlay').hide();
            $('.background-spinner').hide();
            location.reload();
        });
}

function unsetFinishedAll(ordersId, tourId) {
    $('.js_spinner-overlay').show();
    $('.background-spinner').show();
    $.post("/driver/tours/passengers/" + tourId + "/unset_finished_all", {orders_id: ordersId, tour_id: tourId})
        .done(function (data) {
            $('.js_spinner-overlay').hide();
            $('.background-spinner').hide();
            location.reload();
        });
}

function bbvAuth() {
    $('.js_spinner-overlay').show();
    $('.background-spinner').show();
    $.post("/driver/tours/bbv/auth", {})
        .done(function (data) {
            $('.js_spinner-overlay').hide();
            $('.background-spinner').hide();
            bootbox.alert({
                message: '<h2>' + data.message + '</h2>',
                backdrop: true,
                callback: function () {
                    location.reload();
                }
            });
        });
}

function bbvClose(tourId) {
    $('.js_spinner-overlay').show();
    $('.background-spinner').show();
    $.post("/driver/tours/bbv/calc_cash", {tourId: tourId})
        .done(function (data) {
            $('.js_spinner-overlay').hide();
            $('.background-spinner').hide();
            if (data.status == 'success') {
                bootbox.confirm({
                    title: 'Закрытие кассы',
                    message: '<h2> Сумма наличных к сдаче: ' + data.total + ' руб.</h2>',
                    buttons: {
                        cancel: {
                            label: 'Отмена'
                        },
                        confirm: {
                            label: 'Сдал'
                        }
                    },
                    callback: function (result) {
                        if(result)  {
                            $('.js_spinner-overlay').show();
                            $('.background-spinner').show();
                            $.post("/driver/tours/bbv/close", {withdraw: data.total})
                                .done(function (data) {
                                    $('.js_spinner-overlay').hide();
                                    $('.background-spinner').hide();
                                    bootbox.alert({
                                        message: '<h2>' + data.message + '</h2>',
                                        backdrop: true,
                                        callback: function () {
                                            if (data.status == 'success') {
                                                location.reload();
                                            }
                                        }
                                    });
                                });
                        }
                    }
                });
            } else {
                bootbox.alert({
                    message: '<h2>' + data.message + '</h2>',
                    backdrop: true,
                });
            }
        });
}

function bbvReceipt(orderId) {
    $('.js_spinner-overlay').show();
    $('.background-spinner').show();
    $.post("/driver/tours/bbv/receipt", {orderId: orderId})
        .done(function (data) {
            $('.js_spinner-overlay').hide();
            $('.background-spinner').hide();
            bootbox.alert({
                message: '<h3>' + data.message + '</h3>',
                backdrop: true, 
                callback: function () {
                    location.reload();
                }
            });
        });
}

function add(button, tourId, type, clients_phone) {
    try {
        $('.js_spinner-overlay').show();
        $('.background-spinner').show();
        button.disabled = true;
        from = $('#from').val();
        to = $('#to').val();
        count_places = $('#count_places').val();
        first_name = $('#first_name').val();
        phone = $('#country option:selected').attr('code') + $('#phone').val();
        price = $('#price').val();

        last_name = $('#last_name').val() ? $('#last_name').val() : '';
        middle_name = $('#middle_name').val() ? $('#middle_name').val() : '';
        birth_day = $('#birth_day').val() ? $('#birth_day').val() : '';
        country_id = $('#country_id').val() ? $('#country_id').val() : '';
        passport = $('#passport').val() ? $('#passport').val() : '';
        doc_type = $('#doc_type').val() ? $('#doc_type').val() : '';
        doc_number = $('#doc_number').val() ? $('#doc_number').val() : '';
        card = $('#card').val() ? $('#card').val() : '';
        gender = $('#gender').val() ? $('#gender').val() : '';
        flight_number = $('#flight_number').val() ? $('#flight_number').val() : '';

        var type_pay = $('input[name="type_pay"]:checked').val() || 'cash-payment';
    } catch (e) {
        return e;
    }
    var was_pushed = 0;
    if (clients_phone.length == 0) {
        if (was_pushed == 0) {
            postRequest(tourId, from, to, first_name, phone, type, type_pay, price, last_name, middle_name, birth_day, country_id,
                passport, doc_type, doc_number, card, gender, flight_number, count_places);
            was_pushed = 1;
        }
    } else {
        for (var i = clients_phone.length - 1; i >= 0; i--) {
            if (clients_phone[i] != phone.replace(/[^\d;]/g, '')) {
                if (was_pushed == 0) {
                    postRequest(tourId, from, to, first_name, phone, type, type_pay, price, last_name, middle_name, birth_day, country_id,
                        passport, doc_type, doc_number, card, gender, flight_number, count_places);
                    was_pushed = 1;
                }
            } else {
                alert('Этот пассажир уже забронирован на этот рейс!');
                return;
            }
        }
    }
}

function postRequest(tourId, from, to, first_name, phone, type, type_pay, price, last_name, middle_name, birth_day, country_id,
                     passport, doc_type, doc_number, card, gender, flight_number, count_places) {

    let places = [];
    for (let place = 0; place < count_places; place++) {
        places.push('');
    }

    $.post('/driver/tours/passengers/' + tourId + '/add_passenger', {
        api_token: 'wdK5O0BWnpd6CQbmllUBbDDxedV6DSXK',
        tour_id: tourId,
        station_from_id: from,
        station_to_id: to,
        first_name: first_name,
        phone: phone,
        type_pay: type_pay,
        price: price,

        last_name: last_name,
        middle_name: middle_name,
        birth_day: birth_day,
        country_id: country_id,
        passport: passport,
        doc_type: doc_type,
        doc_number: doc_number,
        card: card,
        gender: gender,
        flight_number: flight_number,

        places: places,
    }).done(function (data) {
        $('.js_spinner-overlay').hide();
        $('.background-spinner').hide();
        window.location.href = '/driver/tours/passengers/' + tourId + '/' + type;
    });
}

function maskPhone() {
    var country = $('#country option:selected').val();
    switch (country) {
        case "ua":
            $("#phone").mask("(99) 999-99-99");
            break;
        case "ru":
            $("#phone").mask("(999) 999-99-99");
            break;
        case "by":
            $("#phone").mask("(99) 999-99-99");
            break;
        case "de":
            $("#phone").mask("(999) 9999-9999");
            break;
        case "cz":
            $("#phone").mask("(999) 999-999");
            break;
        case "il":
            $("#phone").mask("(99) 999-9999");
            break;
        case "us":
            $("#phone").mask("(999) 999-9999");
            break;
        case "fi":
            $("#phone").mask("(99) 999-999");
            break;
        case "no":
            $("#phone").mask("(99) 999-999");
            break;
        case "pl":
            $("#phone").mask("(999) 999-999");
            break;
        case "uz":
            $("#phone").mask("(99) 999-99-99");
            break;
        case "tm":
            $("#phone").mask("(999) 999-999");
            break;
        case "md":
            $("#phone").mask("(99) 999-999");
            break;
        case "az":
            $("#phone").mask("(99) 999-99-99");
            break;
        case "tj":
            $("#phone").mask("(9999) 9-99-99");
            break;
        case "fr":
            $("#phone").mask("(999) 999-999");
            break;
        case "gr":
            $("#phone").mask("(999) 999-999");
            break;
    }
}

/*!*******************************************!*\
  !*** ./resources/assets/driver/js/map.js ***!
  \*******************************************/
var addressShow = false;
var map, infoWindow, markerBus, currRend, service, directionsRenderer;
var pos, start, finish;
$(document).on('click', '.js_address_show', function () {
    addressShow = !addressShow;
    addressShow = !addressShow;

    if (addressShow) {
        var createRoute = function createRoute(waypoints) {
            if (!waypoints.length) {
                markerBus.setMap(map);
                infoWindow.open(map);
                map.setCenter(pos);
                console.log("no waypoints");
                return;
            }

            markerBus.setMap(null);
            markerBus = null;
            directionsRenderer.setMap(map);
            start = {
                lat: parseFloat(pos.lat),
                lng: parseFloat(pos.lng)
            };
            finish = {
                lat: parseFloat(waypoints[0].latitude),
                lng: parseFloat(waypoints[0].longitude)
            };
            service.route({
                origin: start,
                destination: finish,
                travelMode: google.maps.TravelMode.DRIVING
            }, function (response, status) {
                if (status === "OK") {
                    directionsRenderer.setDirections(response);
                } else {
                    window.alert("Directions request failed due to " + status);
                }
            });
        };

        pos = {
            lat: 53.9122225,
            lng: 27.4226339
        };
        map = new google.maps.Map(document.getElementById('address_map'), {
            center: pos,
            zoom: 15
        });
        infoWindow = new google.maps.InfoWindow();
        service = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer();

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                markerBus = new google.maps.Marker({
                    position: pos,
                    map: map,
                    icon: '/assets/admin/images/bus-icon.png'
                });
            });
        }

        var tourId = $(this).data('tour');
        var orderId = $(this).data('order');
        $.ajax({
            url: "/driver/routewaypoints",
            type: 'GET',
            dataType: 'json',
            data: {
                tour_id: tourId,
                order_id: orderId
            },
            success: function success(data) {
                return createRoute(data);
            },
            error: function error(data) {
                return createRoute(data);
            }
        });
        $("#address_map").show();
        $("#navigator").show();
    } else {
        $("#address_map").hide();
        $("#navigator").hide();
    }
});
$(document).on('click', '.js_address_show_street', function () {
    addressShow = !addressShow;

    if (addressShow) {
        var myLatLng = {
            lat: parseFloat($(this).attr('latitude')),
            lng: parseFloat($(this).attr('longitude'))
        };

        var _map = new google.maps.Map(document.getElementById('station_map'), {
            center: myLatLng,
            zoom: 15
        });

        new google.maps.Marker({
            position: myLatLng,
            map: _map
        });
        $("#station_map").show();
    } else {
        $("#station_map").hide();
    }
});

/*!**********************************************!*\
  !*** ./resources/assets/driver/js/pusher.js ***!
  \**********************************************/
$(document).ready(function () {
    var id = $('meta[id="tour_id"]').attr('content');
    var env = $('meta[id="env"]').attr('content');

    if (id !== undefined && env !== undefined) {
        var pusher = new Pusher(env, {
            cluster: 'eu',
            encrypted: true
        });
        var channel2 = pusher.subscribe('driver-taxi-channel2');
        var dialogs = [];
        var is_taxi = $('meta[id="is_taxi"]').attr('content');
        channel2.bind('new-taxi-order', function (data) {
            if (data.app_url == APP_URL && is_taxi == 1) {
                navigator.serviceWorker.register('/assets/driver/js/sw.js').then(function (registration) {
                    registration.showNotification("Такси", {
                        body: data.message,
                        icon: '/assets/admin/images/admin-logo.png'
                    });
                });
                dialogs[data.client_id] = bootbox.dialog({
                    title: "Новый заказ!",
                    message: "Заказ через 15 мин:<br> от " + data.from + "<br>до " + data.to,
                    closeButton: true,
                    className: 'js-taxi-order-' + data.client_id,
                    size: 'extra-large',
                    onEscape: true,
                    backdrop: true,
                    buttons: {
                        min5: {
                            label: '5 мин',
                            className: 'btn-success',
                            callback: function callback() {
                                taxiOrder(id, 5, data);
                            }
                        },
                        min10: {
                            label: '10 мин',
                            className: 'btn-success',
                            callback: function callback() {
                                taxiOrder(id, 10, data);
                            }
                        },
                        min15: {
                            label: '15 мин',
                            className: 'btn-success',
                            callback: function callback() {
                                taxiOrder(id, 15, data);
                            }
                        },
                        cancel: {
                            label: 'Пропускаю',
                            className: 'btn-dark'
                        }
                    }
                });
                window.setTimeout(function () {
                    dialogs[data.client_id].modal('hide');
                }, 25000);
            }
        });
        channel2.bind('close-taxi-order', function (data) {
            if (data.app_url == APP_URL) {
                dialogs[data.client_id].modal('hide');
            }
        });

        if (!("Notification" in window)) {
            $.ajax({
                type: "GET",
                async: true,
                cache: true,
                url: "/driver/tours/passengers/" + id + "/get_id",
                success: function success(ids) {
                    var was_pushed = 0;

                    if (ids !== null || ids !== '') {
                        if (was_pushed == 0) {
                            var channel = pusher.subscribe('driver-channel' + ids);
                            channel.bind('my-event', function (datas) {
                                was_pushed = 1;
                                window.location.reload();
                            });
                        }
                    }
                }
            });
        } else if (Notification.permission == "granted") {
            $.ajax({
                type: "GET",
                async: true,
                cache: true,
                url: "/driver/tours/passengers/" + id + "/get_id",
                success: function success(ids) {
                    var was_pushed = 0;

                    if (ids !== null || ids !== '') {
                        if (was_pushed == 0) {
                            var channel = pusher.subscribe('driver-channel' + ids);
                            channel.bind('my-event', function (datas) {
                                if ('serviceWorker' in navigator && 'PushManager' in window) {
                                    was_pushed = 1;
                                    navigator.serviceWorker.register('/assets/driver/js/sw.js').then(function (registration) {
                                        registration.showNotification(datas.message);
                                        window.location.reload();
                                    });
                                }
                            });
                        }
                    }
                }
            });
        } else {
            Notification.requestPermission().then(function (permission) {
                if (permission == "granted") {
                    $.ajax({
                        type: "GET",
                        async: true,
                        cache: true,
                        url: "/driver/tours/passengers/" + id + "/get_id",
                        success: function success(ids) {
                            var was_pushed = 0;

                            if (ids !== null || ids !== '') {
                                if (was_pushed == 0) {
                                    var channel = pusher.subscribe('driver-channel' + ids);
                                    channel.bind('my-event', function (datas) {
                                        if ('serviceWorker' in navigator && 'PushManager' in window) {
                                            was_pushed = 1;

                                            if ('serviceWorker' in navigator && 'PushManager' in window) {
                                                was_pushed = 1;
                                                navigator.serviceWorker.register('/assets/driver/js/sw.js').then(function (registration) {
                                                    registration.showNotification(datas.message);
                                                    window.location.reload();
                                                });
                                            }
                                        }
                                    });
                                }
                            }
                        }
                    });
                }
            });
        }
    }
});

function taxiOrder(id, delay, data) {
    $('.js_spinner-overlay').show();
    $('.background-spinner').show();
    $.post('/driver/tours/taxiorder/' + id + '/add', {
        tour_id: id,
        station_from_id: data.from_id,
        station_to_id: data.to_id,
        client_id: data.client_id,
        delay: delay,
        places: []
    }).done(function (data) {
        $('.js_spinner-overlay').hide();
        $('.background-spinner').hide();
        window.location.reload();
    });
}

/*!************************************************!*\
  !*** ./resources/assets/driver/js/settings.js ***!
  \************************************************/
var settingsShow = false;
var Cookie = "Font Size";
var fontsize = $.cookie(Cookie) || 'normal';
$(document).ready(function () {
    $(".glyphicon-chevron-down").css({
        "top": "8px"
    });

    if (fontsize == "normal") {
        $("body, button, span:not(.glyphicon)").css({
            "font-size": "1.25rem"
        });
        $("#normal").prop("checked", true);
    } else if (fontsize == "larger") {
        $("body, button, span:not(.glyphicon)").css({
            "font-size": "1.4rem"
        });
        $("#larger").prop("checked", true);
    } else {
        $("body, button, span:not(.glyphicon)").css({
            "font-size": "1.6rem"
        });
        $("#biggest").prop("checked", true);
    }

    $(document).on('click', '.js_settings_show', function () {
        settingsShow = !settingsShow;

        if (settingsShow) {
            $("#settings").show();
        } else {
            $("#settings").hide();
        }
    });
});

function biggestFont() {
    $("body, button, span").css({
        "font-size": "1.6rem"
    });
    fontsize = 'biggest';
    $.cookie(Cookie, fontsize);
}

function largerFont() {
    $("body, button, span").css({
        "font-size": "1.4rem"
    });
    fontsize = 'larger';
    $.cookie(Cookie, fontsize);
}

function normalFont() {
    $("body, button, span").css({
        "font-size": "1.25rem"
    });
    fontsize = 'normal';
    $.cookie(Cookie, fontsize);
}

function fillOrder(ordersId, tourId) {
    $('.js_spinner-overlay').show();
    $('.background-spinner').show();
    $.post("/driver/tours/passengers/" + tourId + "/fill_order", {
        order_id: ordersId
    }).done(function (data) {
        $('.js_spinner-overlay').hide();
        $('.background-spinner').hide();
        location.reload();
    });
}

function switchAppearanceOnStation(ordersId, tourId, isAll, stationId) {
    $('.js_spinner-overlay').show();
    $('.background-spinner').show();
    $.post("/driver/tours/passengers/" + tourId + "/switch_appearance_on_station", {
        orders_id: ordersId,
        tour_id: tourId,
        is_all: isAll,
        station_id: stationId
    }).done(function (data) {
        $('.js_spinner-overlay').hide();
        $('.background-spinner').hide();
        location.reload();
    });
}

$(document).on('click', '.set-status-package', setStatusPackage);
$(document).on('click', '.status-package-awaiting', setAwaiting);
$(document).on('click', '.status-package-returned', setReturned);
$(document).on('click', '.status-package-completed', setCompleted);

function setAwaiting(event) {
    let $button = $(event.target)
    $button.attr('style', 'display: none')
    $(event.target).closest('tr').find('.status-package-returned').attr('style', 'display: inline')
    $(event.target).closest('tr').find('.status-package-completed').attr('style', 'display: inline')
}


function setReturned(event) {
    let $button = $(event.target)
    if ($button.hasClass('btn-warning')) {
        $button.removeClass('btn-warning')
        $button.addClass('btn-primary')
        $(event.target).closest('tr').find('.status-package-completed').attr('style', 'display: inline')
        //return
    } else {
        $(event.target).closest('tr').find('.status-package-completed').attr('style', 'display: none')
        $button.removeClass('btn-primary')
        $button.addClass('btn-warning')
    }
}

function setCompleted(event) {
    let $button = $(event.target)
    if ($button.hasClass('btn-success')) {
        $button.removeClass('btn-success')
        $button.addClass('btn-primary')
        $(event.target).closest('tr').find('.status-package-returned').attr('style', 'display: inline')
        //return
    } else {
        $(event.target).closest('tr').find('.status-package-returned').attr('style', 'display: none')
        $button.removeClass('btn-primary')
        $button.addClass('btn-success')
    }
}


function setStatusPackage(event) {
    let $button = $(event.target)
    let url = $button.data('url')
    $.post(url, (response) => {
    })
}
