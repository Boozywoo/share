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