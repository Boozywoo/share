$(document).on('click', '.reservatButon', changeDate);
// $(document).on('change', '.js_reservation-station-from', changeStationFrom)
$(document).on('change', '.js_city_from_id', changeCityFrom);
$(document).on('change', '.js_city_to_id', changeCityTo);
//$(document).on('change', '.js_reservation-station-to', checkStateButtonDisabled)
// $(document).on('change', '.js_reservation-station-to', changeStationTo)
// $(document).on('change', '.js_reservation-route', changeRoute)
$(document).on('click', '.js_reservation-button', clickButton2);
$(document).on('click', '.js_reservation-return-button', reservationReturn);
$(document).on('ready', changeImg);
$(document).on('change', '.js_city_to_id', disableDates);

function changeImg(){
    $.get("/get-rand-img").done(function(data) {
        if(data !== null) {
            if(window.matchMedia("(max-width: 767px)").matches) {
                $('body.mainPage, body.index, body.personalCabinet, body.about').css('background-image', 
                'url(/public/assets/index/images/for_clients/' + data[1] + ')'); 
            } else {
               $('body.mainPage, body.index, body.personalCabinet, body.about').css('background-image', 
                'url(/public/assets/index/images/for_clients/' + data[0] + ')'); 
            }
        }
    });
}

// Автоприменение формы, свели перешли из iframe
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

            clickButton2()
        });

    }

    function isQueryValid() {
        return params.from_embed_form == 1
            && params.from.match(/\d+/)
            && params.to.match(/\d+/)
            && params.date.match(/\d{2}.\d{2}.\d{4}/)
            && (params.return_flag ? params.return_flag.match(/\d{1}/) : true)
        ;
    }
})();

function changeCityFrom() {
    $('.city_to_id').hide();
    $('.js_bus-overlay.js_city_to').show();

    let url = $(this).data().url;
    let station_url = $(this).data().station_url;
    let value = $(this).val();
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

    $.get(url + '?city_from_id=' + value, (response) => {
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
        
        if($('.js_city_to_id') && $('.js_city_to_id').val() && $('#return_flag')) {
            if (parseInt($('#city_to_id').val()) > 0 && $('#city_from_id').val()) {
                $('#return-from').val($("#city_to_id option:selected").text());
                $('#return_city_from_id').val($("#city_to_id").val());
                
                disableDates();
            }

            if($('#return_flag').val() == 1) {
                $('.js-return-ticket, .scheduleBlockReturn').fadeToggle();
                $('.reservRound').eq(0).text(this.checked ? 'Туда' : 'Бронь');

                if($('#return-from').val() && $('#return-to').val()){
                    reservationReturn();
                }
            }
        }

        $('.js_bus-overlay.js_city_to').hide();
        $('.city_to_id').show();
    });
}

function changeCityTo() {
    let button = $('.js_reservation-button');
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
    $.get(url + '?city_id=' + city_id, (response) => {
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
        $.get(url, (response) => {
            show_schedule.html(response);
        })
    }
    return false
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
    return false
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
    let routeId = $(this).val()
    if ($(this).val()) {
        $.post(`/stations?route_id=${routeId}`, (response) => {
            if (response.result = 'success') {
                let $stationFrom = $('.js_reservation-station-from');
                $stationFrom.find(`option`).not(':first').remove();
                $.each(response.stations, function (index, value) {
                    if (index == 0) {
                        $stationFrom.append(`<option selected value="${value.station_id}">${value.name}</option>`);
                        let $stationTo = $('.js_reservation-station-to');
                        $stationTo.attr('disabled', true);
                        $.post(`/stations?route_id=${routeId}&station_from_id=${value.station_id}`, (response) => {
                            if (response.result = 'success') {
                                $stationTo.find(`option`).not(':first').remove();
                                length = response.stations.length;
                                $.each(response.stations, function (index, value) {
                                    if (index === (length - 1))
                                        $stationTo.append(`<option selected value="${value.station_id}">${value.name}</option>`)
                                    else
                                        $stationTo.append(`<option value="${value.station_id}">${value.name}</option>`)
                                });
                            }
                        })
                        checkStateStationToDisabled()
                        checkStateButtonDisabled()
                    } else
                        $stationFrom.append(`<option value="${value.station_id}">${value.name}</option>`);
                });
                checkStateStationToDisabled()
                checkStateButtonDisabled()
                $('.scheduleBlock').html('');
                return;
            }
        })
    }
    $('.scheduleBlock').html('');
    checkStateStationToDisabled()
    checkStateButtonDisabled()
}

function changeStationFrom() {
    let $stationTo = $('.js_reservation-station-to');
    $stationTo.attr('disabled', true);
    let routeId = $('.js_reservation-route').val()
    let stationFromId = $(this).val();
    if (stationFromId) {
        $.post(`/stations?route_id=${routeId}&station_from_id=${stationFromId}`, (response) => {
            if (response.result = 'success') {
                $stationTo.find(`option`).not(':first').remove();

                length = response.stations.length;
                $.each(response.stations, function (index, value) {
                    if (index === (length - 1))
                        $stationTo.append(`<option selected value="${value.station_id}">${value.name}</option>`)
                    else
                        $stationTo.append(`<option value="${value.station_id}">${value.name}</option>`)
                });
                checkStateStationToDisabled()
                checkStateButtonDisabled()
                setTimeout(clickButton, 900);
                return;
            }
        })
    }
    checkStateStationToDisabled()
    checkStateButtonDisabled()
}

function checkStateStationToDisabled() {
    let $stationTo = $('.js_reservation-station-to');
    let disabled = true;
    if ($('.js_reservation-station-from').val()) {
        disabled = false
    } else {
        $stationTo.find(`option`).not(':first').remove();
    }
    $stationTo.attr('disabled', disabled)
}

function checkStateButtonDisabled() {
    let disabled = true;
    if ($('.js_reservation-station-to').val() && $('.js_reservation-station-from').val()) disabled = false
    $('.js_reservation-button').attr('disabled', disabled);
}

function changeStationTo() {
    let disabled = true;
    if ($('.js_reservation-station-to').val() && $('.js_reservation-station-from').val()) disabled = false
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
    }
    //setTimeout(clickButton2, 500);
}

$(document).on('change', '.js_date-pick', changeDatePicker);

function changeDatePicker() {
    var date = $(this).val();
    var $bts = $(this).closest('form').find('.reservatButon');
    $bts.removeClass('active');
    var $btn = $bts.filter('[data-val="' + date + '"]');
    if ($btn.length) $btn.addClass('active');
    //setTimeout(clickButton2, 500);
}

function disableDates() {
    var fromCity = $("select[name='city_from_id']").val();
    var toCity = $("#city_to_id").val();
    if (toCity && fromCity) {
        $('.js_date-pick').hide();
        $('.js_fordate .js_bus-overlay').show();
        $.get('/schedules/get_tour_dates', {city_from_id: fromCity, city_to_id: toCity}, function (array) {
            var html = $('.js_fordate').html();
            $('.js_fordate').html(html);
            $('.js_date-pick').datepicker({
                format: "dd.mm.yyyy",
                todayHighlight: true,
                autoclose: true,
                language: 'ru',
                beforeShowDay: function (date) {
                    var event = new Date(date);
                    event.setDate(event.getDate() + 1);
                    let dateDisable = JSON.stringify(event);
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
    $.get('/schedules/get_tour_dates', { city_from_id: toCity, city_to_id: fromCity }, function (array) {
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
