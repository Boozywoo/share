/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/assets/index/js/app/custom.js":
/*!*************************************************!*\
  !*** ./resources/assets/index/js/app/custom.js ***!
  \*************************************************/
/***/ (() => {

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
$(document).on('submit', '.js_ajax-form', submitAjaxForm);

window.processAjaxSubmit = function ($form, onSuccess, onError) {
  return function (response) {
    $form.trigger('form-ajax', [response]);

    if (response.result == 'success') {
      $form.trigger('form-ajax-success', [response]);
      if (response.message) toastr.success(response.message);
      if (response.redirect) setTimeout(function () {
        return window.location.href = response.redirect;
      }, 0);

      if ($form.hasClass('js_form-step-order')) {
        setTimeout(function () {
          return window.location.href = '/order';
        }, 0);
      }

      ;
    } else {
      $form.trigger('form-ajax-error', [response]);
      toastr.error(response.message || 'Ошибка при отправке формы!');
    }

    $form.find('input[type="submit"]').attr('disabled', false);
    $form.find('.js_btn').attr('disabled', false);
  };
};

function submitAjaxForm() {
  var $form = $(this);
  $form.find('input[type="submit"]').attr('disabled', true);
  $form.find('.js_btn').attr('disabled', true);
  $form.ajaxSubmit({
    data: {
      'is_ajax': 1
    },
    success: window.processAjaxSubmit($form)
  });
  return false;
}

function init() {
  maskPhone();
}

init();

function maskPhone() {// $('.js_mask-phone').inputmask('+375 (99) 999-99-99');
}

function datePicker() {
  $.fn.datepicker.dates['ru'] = {
    days: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"],
    daysShort: ["Вск", "Пнд", "Втр", "Срд", "Чтв", "Птн", "Суб"],
    daysMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
    months: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
    monthsShort: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
    today: "Сегодня",
    clear: "Очистить",
    format: "dd.mm.yyyy",
    weekStart: 1
  };
  $('.js_date-pick').datepicker({
    format: "dd.mm.yyyy",
    todayHighlight: true,
    autoclose: true,
    language: 'ru'
  });
  $('.js_datepicker').datepicker({
    format: "dd.mm.yyyy",
    todayHighlight: true,
    autoclose: true,
    language: 'ru'
  });
}

window.datePicker = datePicker;
datePicker();

/***/ }),

/***/ "./resources/assets/index/js/app/pages/home.js":
/*!*****************************************************!*\
  !*** ./resources/assets/index/js/app/pages/home.js ***!
  \*****************************************************/
/***/ (() => {

$(document).on('click', '.reservatButon', changeDate); // $(document).on('change', '.js_reservation-station-from', changeStationFrom)

$(document).on('change', '.js_city_from_id', changeCityFrom);
$(document).on('change', '.js_city_to_id', changeCityTo); //$(document).on('change', '.js_reservation-station-to', checkStateButtonDisabled)
// $(document).on('change', '.js_reservation-station-to', changeStationTo)
// $(document).on('change', '.js_reservation-route', changeRoute)

$(document).on('click', '.js_reservation-button', clickButton2);
$(document).on('click', '.js_reservation-return-button', reservationReturn);
$(document).on('ready', changeImg);
$(document).on('change', '.js_city_to_id', disableDates);

function changeImg() {
  $.get("/get-rand-img").done(function (data) {
    if (data !== null) {
      if (window.matchMedia("(max-width: 767px)").matches) {
        $('body.mainPage, body.index, body.personalCabinet, body.about').css('background-image', 'url(/public/assets/index/images/for_clients/' + data[1] + ')');
      } else {
        $('body.mainPage, body.index, body.personalCabinet, body.about').css('background-image', 'url(/public/assets/index/images/for_clients/' + data[0] + ')');
      }
    }
  });
} // Автоприменение формы, свели перешли из iframe


(function () {
  var $form = document.querySelector('form#reservations');
  var $from = document.querySelector('.js_city_from_id');
  var $to = document.querySelector('.js_city_to_id');
  var $date = document.querySelector('.js_date-pick');
  var $return_flag = document.querySelector('#return_flag');
  var params = {};

  if (isAllElementsExists()) {
    init();
  }

  function isAllElementsExists() {
    return $form && $from && $to && $date;
  }

  function init() {
    setParams();

    if (isQueryValid()) {
      setForm();
    }
  }

  function setParams() {
    var url = location.href;
    url = url.split('?');

    if (url[1]) {
      var query = url[1].split('&');

      for (var i = 0; i < query.length; i++) {
        var pair = query[i].split('=');

        if (pair[1]) {
          params[pair[0]] = pair[1];
        }
      }
    }
  }

  function setForm() {
    $date.value = params.date;
    var date = $($date).val();
    var $bts = $($date).closest('form').find('.reservatButon');
    $bts.removeClass('active');
    var $btn = $bts.filter('[data-val="' + date + '"]');
    if ($btn.length) $btn.addClass('active');
    $from.value = params.from;
    params.return_flag ? $return_flag.value = params.return_flag : $return_flag = null;
    var url = $($from).data().url;
    var station_url = $($from).data().station_url;
    var value = $($from).val();
    $(".js_city_to_id option").remove();
    $('.js_city_to_id').prop('disabled', false);
    $(".js_reservation-station-from option").remove();
    $('.js_reservation-station-from').append($('<option>', {
      value: 0,
      text: 'Место посадки (Откуда)'
    }));
    $(".js_reservation-station-to option").remove();
    $('.js_reservation-station-to').append($('<option>', {
      value: 0,
      text: 'Место высадки (Куда)'
    }));
    $.get(url + '?city_from_id=' + value, function (response) {
      $('.js_city_to_id').append($('<option>', {
        value: 0,
        text: 'Куда'
      }));

      for (var key in response) {
        $('.js_city_to_id').append($('<option>', {
          value: response[key].id,
          text: response[key].name
        }));
      }

      $to.value = params.to;
      changeCityTo();
      clickButton2();
    });
  }

  function isQueryValid() {
    return params.from_embed_form == 1 && params.from.match(/\d+/) && params.to.match(/\d+/) && params.date.match(/\d{2}.\d{2}.\d{4}/) && (params.return_flag ? params.return_flag.match(/\d{1}/) : true);
  }
})();

function changeCityFrom() {
  var _this = this;

  $('.city_to_id').hide();
  $('.js_bus-overlay.js_city_to').show();
  var url = $(this).data().url;
  var station_url = $(this).data().station_url;
  var value = $(this).val();
  $(".js_city_to_id option").remove();
  $('.js_city_to_id').prop('disabled', false);
  $(".js_reservation-station-from option").remove();
  $('.js_reservation-station-from').append($('<option>', {
    value: 0,
    text: 'Место посадки (Откуда)'
  }));
  $(".js_reservation-station-to option").remove();
  $('.js_reservation-station-to').append($('<option>', {
    value: 0,
    text: 'Место высадки (Куда)'
  }));
  $.get(url + '?city_from_id=' + value, function (response) {
    $('.js_city_to_id').append($('<option>', {
      value: 0,
      text: 'Куда'
    }));

    for (var key in response) {
      $('.js_city_to_id').append($('<option>', {
        value: response[key].id,
        text: response[key].name
      }));
    }

    if ($('.js_city_to_id') && $('.js_city_to_id').val() && $('#return_flag')) {
      if (parseInt($('#city_to_id').val()) > 0 && $('#city_from_id').val()) {
        $('#return-from').val($("#city_to_id option:selected").text());
        $('#return_city_from_id').val($("#city_to_id").val());
        disableDates();
      }

      if ($('#return_flag').val() == 1) {
        $('.js-return-ticket, .scheduleBlockReturn').fadeToggle();
        $('.reservRound').eq(0).text(_this.checked ? 'Туда' : 'Бронь');

        if ($('#return-from').val() && $('#return-to').val()) {
          reservationReturn();
        }
      }
    }

    $('.js_bus-overlay.js_city_to').hide();
    $('.city_to_id').show();
  });
}

function changeCityTo() {
  var button = $('.js_reservation-button');
  button.attr('disabled', false);
  /*$.get(get_route_url + '?from_city_id=' + from_city_id + '&to_city_id=' + to_city_id, (response) => {
      $( "input[name='route_id']").val(response.route_id);
  $(".js_reservation-station-from option").remove();
  for (var key in response.from_stations) {
      $('.js_reservation-station-from').append($('<option>', {
          value: response.from_stations[key].id,
          text: response.from_stations[key].name
      }));
  }
  $(".js_reservation-station-to option").remove();
  for (var key in response.to_stations) {
      $('.js_reservation-station-to').append($('<option>', {
          value: response.to_stations[key].id,
          text: response.to_stations[key].name
      }));
  }
  });*/
}

function setStations(js_class, url, city_id) {
  $("." + js_class + " option").remove();
  $.get(url + '?city_id=' + city_id, function (response) {
    for (var key in response) {
      $('.' + js_class).append($('<option>', {
        value: response[key].id,
        text: response[key].name
      }));
    }
  });
}

function clickButton() {
  if ($('.js_reservation-button').attr('disabled')) {
    toastr.error('Выберите места посадки и высадки');
  } else {
    var url = $('.js_reservation-button').attr('data-url') + '?' + $('.js_reservation-button').closest('form').serialize();
    var show_schedule = $('.scheduleBlock');
    $.get(url, function (response) {
      show_schedule.html(response);
    });
  }

  return false;
}

function clickButton2() {
  if ($('.js_reservation-button').attr('disabled')) {
    toastr.error('Выберите города посадки и высадки');
  } else {
    var $embedForm = document.querySelector('.js-embed-form');

    if ($embedForm) {
      if (typeof sendEmbedReservationForm === "function") {
        sendEmbedReservationForm();
      }
    } else {
      showTours();
    }
  }

  return false;
}

function showTours() {
  $('.scheduleBlock').html('');
  $('.sk-fading-circle.first').show();
  var url = $('.js_reservation-button').attr('data-url') + '?' + $('.js_reservation-button').closest('form').serialize();
  var show_schedule = $('.scheduleBlock');
  $.get(url, function (response) {
    $('.sk-fading-circle.first').hide();

    if (response.result == 'success') {
      if (show_schedule.length) {
        show_schedule.html(response.html);
        $('html, body').animate({
          scrollTop: show_schedule.offset().top
        }, 300);
      }
    } else {
      toastr.error(response.message);
    }
  });
}

function reservationReturn() {
  if (!$('#return_city_to_id').val() || !$('#return_city_from_id').val()) {
    toastr.error('Выберите города посадки и высадки');
  } else {
    $('.scheduleBlockReturn').html('');
    $('.sk-fading-circle.second').show();
    var url = $('.js_reservation-button').attr('data-url') + '?' + $('.js_reservation-return-button').closest('form').serialize();
    var show_schedule = $('.scheduleBlockReturn');
    $.get(url, function (response) {
      $('.sk-fading-circle.second').hide();

      if (response.result == 'success') {
        show_schedule.html(response.html);
        $('html, body').animate({
          scrollTop: show_schedule.offset().top
        }, 300);
        show_schedule.prepend('<input type="hidden" class="return-form" value="1"/>');
      } else {
        toastr.error(response.message);
      }
    });
  }

  return false;
}

function changeRoute() {
  var routeId = $(this).val();

  if ($(this).val()) {
    $.post("/stations?route_id=".concat(routeId), function (response) {
      if (response.result = 'success') {
        var $stationFrom = $('.js_reservation-station-from');
        $stationFrom.find("option").not(':first').remove();
        $.each(response.stations, function (index, value) {
          if (index == 0) {
            $stationFrom.append("<option selected value=\"".concat(value.station_id, "\">").concat(value.name, "</option>"));
            var $stationTo = $('.js_reservation-station-to');
            $stationTo.attr('disabled', true);
            $.post("/stations?route_id=".concat(routeId, "&station_from_id=").concat(value.station_id), function (response) {
              if (response.result = 'success') {
                $stationTo.find("option").not(':first').remove();
                length = response.stations.length;
                $.each(response.stations, function (index, value) {
                  if (index === length - 1) $stationTo.append("<option selected value=\"".concat(value.station_id, "\">").concat(value.name, "</option>"));else $stationTo.append("<option value=\"".concat(value.station_id, "\">").concat(value.name, "</option>"));
                });
              }
            });
            checkStateStationToDisabled();
            checkStateButtonDisabled();
          } else $stationFrom.append("<option value=\"".concat(value.station_id, "\">").concat(value.name, "</option>"));
        });
        checkStateStationToDisabled();
        checkStateButtonDisabled();
        $('.scheduleBlock').html('');
        return;
      }
    });
  }

  $('.scheduleBlock').html('');
  checkStateStationToDisabled();
  checkStateButtonDisabled();
}

function changeStationFrom() {
  var $stationTo = $('.js_reservation-station-to');
  $stationTo.attr('disabled', true);
  var routeId = $('.js_reservation-route').val();
  var stationFromId = $(this).val();

  if (stationFromId) {
    $.post("/stations?route_id=".concat(routeId, "&station_from_id=").concat(stationFromId), function (response) {
      if (response.result = 'success') {
        $stationTo.find("option").not(':first').remove();
        length = response.stations.length;
        $.each(response.stations, function (index, value) {
          if (index === length - 1) $stationTo.append("<option selected value=\"".concat(value.station_id, "\">").concat(value.name, "</option>"));else $stationTo.append("<option value=\"".concat(value.station_id, "\">").concat(value.name, "</option>"));
        });
        checkStateStationToDisabled();
        checkStateButtonDisabled();
        setTimeout(clickButton, 900);
        return;
      }
    });
  }

  checkStateStationToDisabled();
  checkStateButtonDisabled();
}

function checkStateStationToDisabled() {
  var $stationTo = $('.js_reservation-station-to');
  var disabled = true;

  if ($('.js_reservation-station-from').val()) {
    disabled = false;
  } else {
    $stationTo.find("option").not(':first').remove();
  }

  $stationTo.attr('disabled', disabled);
}

function checkStateButtonDisabled() {
  var disabled = true;
  if ($('.js_reservation-station-to').val() && $('.js_reservation-station-from').val()) disabled = false;
  $('.js_reservation-button').attr('disabled', disabled);
}

function changeStationTo() {
  var disabled = true;
  if ($('.js_reservation-station-to').val() && $('.js_reservation-station-from').val()) disabled = false;
  $('.js_reservation-button').attr('disabled', disabled);
  setTimeout(clickButton, 300);
}

function changeDate() {
  var val = $(this).data('val');
  var $date = $(this).closest('form').find('.js_date-pick');
  $(this).closest('form').find('.reservatButon').removeClass('active');
  $(this).addClass('active');
  $date.val(val);
  $date.datepicker('setDate', val);

  if ($(this).data('return')) {
    reservationReturn();
  } else {
    showTours();
  } //setTimeout(clickButton2, 500);

}

$(document).on('change', '.js_date-pick', changeDatePicker);

function changeDatePicker() {
  var date = $(this).val();
  var $bts = $(this).closest('form').find('.reservatButon');
  $bts.removeClass('active');
  var $btn = $bts.filter('[data-val="' + date + '"]');
  if ($btn.length) $btn.addClass('active'); //setTimeout(clickButton2, 500);
}

function disableDates() {
  var fromCity = $("select[name='city_from_id']").val();
  var toCity = $("#city_to_id").val();

  if (toCity && fromCity) {
    $('.js_date-pick').hide();
    $('.js_fordate .js_bus-overlay').show();
    $.get('/schedules/get_tour_dates', {
      city_from_id: fromCity,
      city_to_id: toCity
    }, function (array) {
      var html = $('.js_fordate').html();
      $('.js_fordate').html(html);
      $('.js_date-pick').datepicker({
        format: "dd.mm.yyyy",
        todayHighlight: true,
        autoclose: true,
        language: 'ru',
        beforeShowDay: function beforeShowDay(date) {
          var event = new Date(date);
          event.setDate(event.getDate() + 1);
          var dateDisable = JSON.stringify(event);
          dateDisable = dateDisable.slice(1, 11);
          return array.indexOf(dateDisable) !== -1;
        }
      });

      if (array.length) {
        var date1 = array[0];
        date1 = date1.split('-');
        $('.js_date-pick').datepicker('setDate', date1[2] + '.' + date1[1] + '.' + date1[0]);
      }

      $('.js_date-pick').show();
      $('.js_fordate .js_bus-overlay').hide();
    });
  }

  $.get('/schedules/get_tour_dates', {
    city_from_id: toCity,
    city_to_id: fromCity
  }, function (array) {
    var html = $('.js_fordate2').html();
    $('.js_fordate2').html(html);
    $('.js_date-pick-return').datepicker({
      format: "dd.mm.yyyy",
      todayHighlight: true,
      autoclose: true,
      language: 'ru',
      beforeShowDay: function beforeShowDay(date) {
        var event = new Date(date);
        event.setDate(event.getDate() + 1);
        var dateDisable = JSON.stringify(event);
        dateDisable = dateDisable.slice(1, 11);
        return array.indexOf(dateDisable) !== -1;
      }
    });

    if (array.length) {
      var date1 = array[0];
      date1 = date1.split('-');
      $('.js_date-pick-return').datepicker('setDate', date1[2] + '.' + date1[1] + '.' + date1[0]);
    }
  });
}

/***/ }),

/***/ "./resources/assets/index/js/app/pages/order.js":
/*!******************************************************!*\
  !*** ./resources/assets/index/js/app/pages/order.js ***!
  \******************************************************/
/***/ (() => {

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
  data['station_to_id'] = $("[name='station_to_id" + returnTxt + "']").val();
  data['destination'] = 'from';
  data['return_ticket'] = $(this).data('return');
  var is_return = data['return_ticket'] ? '.return-tickets' : '.direct-tickets';
  $.get(url, data).success(function (data) {
    if (data.result == 'error') {
      toastr.error(data.message);
      $("[name='station_from_id" + returnTxt + "']").val(data.station_from_id);
      $("[name='station_to_id" + returnTxt + "']").val(data.station_to_id);
    } else {
      toastr.success(data.message);
      $('.js_time_from' + returnTxt).html(data.DateFrom);
      $('.js_time_to' + returnTxt).html(data.DateTo);
      $('.js_order-prices' + is_return).html(data.htmlPrices);
    }
  });
}

function SelectOrderStationTo() {
  var data = {};
  var url = $(this).data('url');
  var returnTxt = $(this).data('return') ? '_return' : '';
  data['station_from_id'] = $("[name='station_from_id" + returnTxt + "']").val();
  data['station_to_id'] = $(this).val();
  data['destination'] = 'to';
  data['return_ticket'] = $(this).data('return');
  var is_return = data['return_ticket'] ? '.return-tickets' : '.direct-tickets';
  $.get(url, data).success(function (data) {
    if (data.result == 'error') {
      toastr.error(data.message);
      $("[name='station_from_id" + returnTxt + "']").val(data.station_from_id);
      $("[name='station_to_id" + returnTxt + "']").val(data.station_to_id);
    } else {
      toastr.success(data.message);
      $('.js_time_from' + returnTxt).html(data.DateFrom);
      $('.js_time_to' + returnTxt).html(data.DateTo);
      $('.js_order-prices' + is_return).html(data.htmlPrices);
    }
  });
}

function couponBtn() {
  var url = $(this).data('url');
  var code = $('.js_form-coupon-code').val();
  var $prices = $('.js_order-prices');
  $.get("".concat(url, "?code=").concat(code), function (response) {
    if (response.result == 'success') {
      toastr.success(response.message);
    } else {
      toastr.error(response.message);
    }

    $prices.html(response.view);
  });
}

function orderForm() {
  var $form = $('.js_form-order');
  $form.submit();
  var fewSeconds = 5;
  var btn = $(this);
  btn.prop('disabled', true);
  setTimeout(function () {
    btn.prop('disabled', false);
  }, fewSeconds * 1000);
}

function orderPayForm() {
  $('<input>').attr({
    type: 'hidden',
    id: 'is_pay',
    name: 'is_pay',
    value: 1
  }).appendTo('form');
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
  var $popup = $('#js_email-popup');
  $popup.html('');
}

function sendPDFEmail() {
  var id = $(this).attr('data-order-id');
  var url = '/profile/generate-pdf-to-email/' + id;
  var $popup = $('#js_email-popup');
  var $this = this;
  $("#js_ticket-active").attr("data-ticket-id", id);
  var button_message_waiting = 'Обрабатывается...';
  var button_message_again = 'Отправить еще раз?';
  $($this).html(button_message_waiting);
  $.get(url, function (response) {
    if (response.result == 'success') {
      toastr.success(response.message);
    } else {
      toastr.error(response.message);
      $popup.html(response.view);
      $('#popup1').show();
    }

    $($this).html(button_message_again);
  });
}

function sendEmail() {
  var url = '/profile/update/email';
  var email = $('#settings-input').val();
  $.post(url, {
    email: email
  }, function (data) {
    if (data.result == 'error') {
      toastr.error(data.message);
    } else {
      toastr.success('Данные успешно обновлены');
      closePopup();
      $(".js_send-to-email").trigger("click");
    }
  });
}

/***/ }),

/***/ "./resources/assets/index/js/app/pages/profile.js":
/*!********************************************************!*\
  !*** ./resources/assets/index/js/app/pages/profile.js ***!
  \********************************************************/
/***/ (() => {

$(document).on('form-ajax-success', '.js_settings-form', submitSettingSuccess);
$(document).on('click', '.js_settings-edit', editSetting);
$(document).on('click', '.js_settings-save', saveSetting);
$(document).on('click', '.js_tickets-cancel', ticketCancel);

function ticketCancel() {
  var orderId = $('.js_tickets-popup .techHiddenInput').val();
  $.post('/profile/tickets', {
    id: orderId
  }, function (response) {
    if (response.result == 'success') {
      toastr.success(response.message);
      $(".js_tickets-tr[data-id=".concat(orderId, "]")).remove();
    } else {
      toastr.error(response.message);
    }

    $('.js_tickets-close_popup').click();
  });
}

function saveSetting() {
  $('.js_settings-form').submit();
  return false;
}

function editSetting() {
  $('.js_settings-edit').hide();
  $('.js_settings-save').show();
  $('.js_settings-form').find('.js_settings-input').attr('disabled', false);
  return false;
}

function submitSettingSuccess() {
  $('.js_settings-edit').show();
  $('.js_settings-save').hide();
  $('.js_settings-form').find('.js_settings-input').attr('disabled', true);
}

/***/ }),

/***/ "./resources/assets/index/js/app/pages/schedule.js":
/*!*********************************************************!*\
  !*** ./resources/assets/index/js/app/pages/schedule.js ***!
  \*********************************************************/
/***/ (() => {

$(document).on('click', '.js_get-bus', getBus);
$(document).on('click', '.js_tour-disable-order', TourDisableOrder);
$(document).on('click', '.seat:not(.reserved)', clickSeat);
$(document).on('change', '.js_orders-count_places', clickSeat);
$(document).on('click', '.js_form-places-btn', continueOrder);
$(document).on('form-ajax', '.js_form-places', eventPlacesForm);

function TourDisableOrder() {
  $('html, body').animate({
    scrollTop: $(".blocksWrapper").offset().top
  }, 500);
  var time = $(this).data('time');
  toastr.warning('Бронирование на рейс за ' + time + ' мин до отправления недоступно через сайт, Вы можете забронировать билет с помощью оператора. Благодарим за понимание');
}

function eventPlacesForm(e, response) {
  if (response.view) {
    if (response.return_ticket) {
      $('.scheduleBlockReturn .js_get-bus-row-bus:visible').html(response.view);
    } else {
      $('.scheduleBlock .js_get-bus-row-bus:visible').html(response.view);
    }
  }

  $('.js_bus-overlay').hide();
}

function getBus() {
  if (!$(this).hasClass('disabled')) {
    var $getBus = $(this).closest('.shedule').find('.js_get-bus-row');
    $getBus.addClass('disabled');
    var url = $(this).data('url');
    var $wrapper = $(this).closest('.sheduleRow').next('.js_get-bus-row');

    if (!$wrapper.hasClass('active')) {
      $getBus.removeClass('active').hide();
      $.post(url, {
        return_ticket: $(this).data('return'),
        places: $(this).data('places')
      }, function (response) {
        if (response.result == 'success') {
          $wrapper.show();
          $wrapper.addClass('active');
          $wrapper.find('.js_get-bus-row-bus').html(response.view);
          $wrapper.find('.js_orders-count_places').trigger('change'); // $wrapper.find('.js_bus-wrap').height($wrapper.find('.js_get-bus-row-bus').width() - 150)
        } else {
          toastr.error(response.message);
        }

        $getBus.removeClass('disabled');
      });
    } else {
      $wrapper.removeClass('active').hide();
      $getBus.removeClass('disabled');
    }
  }

  return false;
}

function continueOrder() {
  if ($('#return_flag > option:selected').val() == 1) {
    var places = $('.scheduleBlock .js_get-bus-row-bus .cell.active:visible').length + parseInt($('.scheduleBlock .js_orders-count_places').val() || 0); // Кол-во мест туда

    var places2 = $('.scheduleBlockReturn .js_get-bus-row-bus .cell.active:visible').length + parseInt($('.scheduleBlockReturn .js_orders-count_places').val() || 0); // Кол-во мест обратно

    if (places !== places2) {
      toastr.error('Количество мест туда и обратно должны совпадать!');
      return false;
    }

    if (places == 0) {
      toastr.error('Выберите места!');
      return false;
    }
  }

  clickSeat(true, $(this));
}

function clickSeat(order, $this) {
  $('.js_bus-overlay').show();
  $this = $this ? $this : $(this);
  var $wrapper = $this.closest('.js_get-bus-row');
  var $form = $wrapper.find('.js_form-places');
  var $wrapperInput = $form.find('.js_form-places-inputs');
  $wrapperInput.html('');
  $wrapperInput.prepend('<input type="hidden" name="return_ticket" value="' + $this.closest('.shedulePage').parent().find('.return-form').eq(0).val() + '"/>');
  var $countPlaces = $wrapper.find('.js_orders-count_places');

  if ($countPlaces.length) {
    var val = $countPlaces.val();

    for (var $i = 0; $i < val; $i++) {
      $wrapperInput.prepend("<input type=\"hidden\" name=\"places[]\" value=\"\"/>");
    }
  } else {
    if (parseInt(cnt_reserved_places_tour) >= parseInt(limit_order_by_place) && $(this).hasClass('active') !== true && order !== true) {
      toastr.error("Бронирование ограничено");
    } else $(this).toggleClass('active');

    $wrapper.find('.seat.active:not(.reserved)').each(function () {
      var number = $(this).data('number');
      $wrapperInput.prepend("<input type=\"hidden\" name=\"places[]\" value=\"".concat(number, "\"/>"));
    });
  }

  if (order === true) $form.addClass('js_form-step-order');
  $form.submit();
  return false;
}

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
/*!**********************************************!*\
  !*** ./resources/assets/index/js/app/app.js ***!
  \**********************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _custom__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./custom */ "./resources/assets/index/js/app/custom.js");
/* harmony import */ var _custom__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_custom__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _pages_profile__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./pages/profile */ "./resources/assets/index/js/app/pages/profile.js");
/* harmony import */ var _pages_profile__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_pages_profile__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _pages_schedule__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./pages/schedule */ "./resources/assets/index/js/app/pages/schedule.js");
/* harmony import */ var _pages_schedule__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_pages_schedule__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _pages_order__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./pages/order */ "./resources/assets/index/js/app/pages/order.js");
/* harmony import */ var _pages_order__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_pages_order__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _pages_home__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./pages/home */ "./resources/assets/index/js/app/pages/home.js");
/* harmony import */ var _pages_home__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_pages_home__WEBPACK_IMPORTED_MODULE_4__);





})();

/******/ })()
;