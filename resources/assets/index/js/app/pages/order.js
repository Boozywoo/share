$(document).on('click', '.js_form-order-btn', orderForm);
$(document).on('click', '.js_form-order-pay-btn', orderPayForm);
$(document).on('click', '.js_form-coupon-btn', couponBtn);
$('.js_orders-count_places_child').change(setChild);
$(document).on('change', '.js_select_station_from', SelectOrderStationFrom);
$(document).on('change', '.js_select_station_to', SelectOrderStationTo);
$(document).on('change', '#country-codes', SelectCodeCountry);

$(document).on('click', '.js_send-to-email', sendPDFEmail);
$(document).on('click', '.js_submit-email', sendEmail);
$(document).on('click', '.modal span.close', closePopup);

$(document).on('ready', maskPhone);


function maskPhone() {
    var country = $('#country-codes option:selected').val();
    switchCountry(country);
}

function switchCountry(country) {
    switch (country) {
        case "ru":
            $(".js_mask-phone").inputmask("+7(999) 999-99-99");
            break;
        case "ua":
            $(".js_mask-phone").inputmask("+380(99) 999-99-99");
            break;
        case "by":
            $(".js_mask-phone").inputmask("+375(99) 999-99-99");
            break;
        case "de":
            $(".js_mask-phone").inputmask("+4\\9(999) 999-99-99");
            break;
        case "dee":
            $(".js_mask-phone").inputmask("+4\\9(999) 999-99-999");
            break;
        case "cz":
            $(".js_mask-phone").inputmask("+420(999) 999-99-99");
            break;
        case "il":
            $(".js_mask-phone").inputmask("+\\972(99) 999-99-99");
            break;
        case "us":
            $(".js_mask-phone").inputmask("+1(999) 999-99-99");
            break;
        case "fi":
            $(".js_mask-phone").inputmask("+358(99) 999-99-99");
            break;
        case "no":
            $(".js_mask-phone").inputmask("+47(999) 999-99-99");
            break;
        case "pl":
            $(".js_mask-phone").inputmask("+48(999) 999-99-99");
            break;
        case "uz":
            $(".js_mask-phone").inputmask("+\\9\\98(99) 999-99-99");
            break;
        case "tm":
            $(".js_mask-phone").inputmask("+\\9\\93(999) 999-99-99");
            break;
        case "md":
            $(".js_mask-phone").inputmask("+373(99) 999-99-99");
            break;
        case "az":
            $(".js_mask-phone").inputmask("+\\9\\94(99) 999-99-99");
            break;
        case "tj":
            $("#phone").inputmask("+\\9\\92(9999) 9-99-99");
            break;
        case "fr":
            $("#phone").inputmask("+33(999) 999-999");
            break;
    }
}

function SelectCodeCountry() {
    switchCountry($(this).val());
}

maskPhone();

function SelectOrderStationFrom() {
    var data = {};
    var url = $(this).data('url');
    var returnTxt = $(this).data('return') ? '_return' : '';
    data['station_from_id'] = $(this).val();
    data['station_to_id'] = $("[name='station_to_id"+returnTxt+"']").val();
    data['destination'] = 'from';
    data['return_ticket'] = $(this).data('return');
    var is_return = data['return_ticket'] ? '.return-tickets' : '.direct-tickets';

    $.get(url, data).success(function (data) {
        if (data.result == 'error') {
            toastr.error(data.message);
            $("[name='station_from_id"+returnTxt+"']").val(data.station_from_id);
            $("[name='station_to_id"+returnTxt+"']").val(data.station_to_id);
        } else {
            toastr.success(data.message);
            $('.js_time_from'+returnTxt).html(data.DateFrom);
            $('.js_time_to'+returnTxt).html(data.DateTo);
            $('.js_order-prices'+is_return).html(data.htmlPrices);
        }
    });
}

function SelectOrderStationTo() {
    var data = {};
    var url = $(this).data('url');
    var returnTxt = $(this).data('return') ? '_return' : '';
    data['station_from_id'] = $("[name='station_from_id"+returnTxt+"']").val();
    data['station_to_id'] = $(this).val();
    data['destination'] = 'to';
    data['return_ticket'] = $(this).data('return');
    var is_return = data['return_ticket'] ? '.return-tickets' : '.direct-tickets';

    $.get(url, data).success(function (data) {
        if (data.result == 'error') {
            toastr.error(data.message);
            $("[name='station_from_id"+returnTxt+"']").val(data.station_from_id);
            $("[name='station_to_id"+returnTxt+"']").val(data.station_to_id);
        } else {
            toastr.success(data.message);
            $('.js_time_from'+returnTxt).html(data.DateFrom);
            $('.js_time_to'+returnTxt).html(data.DateTo);
            $('.js_order-prices'+is_return).html(data.htmlPrices);
        }
    });
}


function couponBtn() {
    let url = $(this).data('url');
    let code = $('.js_form-coupon-code').val();
    let $prices = $('.js_order-prices');
    $.get(`${url}?code=${code}`, (response) => {
        if (response.result == 'success') {
            toastr.success(response.message);
        } else {
            toastr.error(response.message);
        }
        $prices.html(response.view);
    })
}

function orderForm() {
    let $form = $('.js_form-order');
    $form.submit();
    var fewSeconds = 5;
    var btn = $(this);
    btn.prop('disabled', true);
    setTimeout(function () {
        btn.prop('disabled', false);
    }, fewSeconds * 1000);
}

function orderPayForm() {
    $('<input>').attr({type: 'hidden', id: 'is_pay', name: 'is_pay', value: 1}).appendTo('form');
    orderForm();
}

function setChild() {
    var url = $(this).data('url');
    var $prices = $('.js_order-prices');
    var $total = $('#total-ticket');
    var count = $(this).val();
    $.get(url + '?count=' + count, function (response) {
        if (response.result == 'success') {
            toastr.success(response.message);
        } else {
            toastr.error(response.message);
        }
        $prices.html(response.view);
        $total.html(response.total);
    });
}

function closePopup() {
    let $popup = $('#js_email-popup');
    $popup.html('');
}

function sendPDFEmail() {
    let id = $(this).attr('data-order-id');
    let url = '/profile/generate-pdf-to-email/' + id;
    let $popup = $('#js_email-popup');
    let $this = this;

    $("#js_ticket-active").attr("data-ticket-id", id);

    let button_message_waiting = 'Обрабатывается...';
    let button_message_again = 'Отправить еще раз?';

    $($this).html(button_message_waiting);
    $.get(url, (response) => {
        if (response.result == 'success') {
            toastr.success(response.message);
        } else {
            toastr.error(response.message);
            $popup.html(response.view);
            $('#popup1').show();
        }
        $($this).html(button_message_again);
    })
}

function sendEmail() {

    let url = '/profile/update/email';
    let email = $('#settings-input').val();

    $.post(url, {
        email: email
    }, function (data) {
        if (data.result == 'error') {
            toastr.error(data.message);
        } else {
            toastr.success('Данные успешно обновлены');
            closePopup();
            $(".js_send-to-email").trigger("click")
        }
    });
}