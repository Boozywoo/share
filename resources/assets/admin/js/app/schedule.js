$(document).ready(function () {

    $(document).on('change', '.js_bus-change', templateChange);
    $(document).on('change', '.js_route-change', routeChange);
    $(document).on('change', '.js-days-yes-or-no', selectNoOrYesOnShedule);
    $(document).on('click', '.js-button-copy', copyPrices);
    $(document).on('click', '.js_change_sched_price', changeSchedPrice);

    $('.js_route-change').trigger('change');
    $('.js-days-yes-or-no').trigger('change');

    function templateChange() {
        let url = $(this).data('url')
        let val = $(this).val()
        $.get(`${url}/${val}`, (response) => {
            if (response.val) {
                $('.js_driver-select').val(response.val)
            }
        })
    }

    function routeChange() {
        var url = $(this).data('url');
        var val = $(this).val();
        $.get(url + '/' + val, function (response) {
            if (response.data) {
                let data = response.data;
                if (data['is_transfer'])  {
                    $('.js_flight-data').slideDown();
                    $('#flight-offset').data('interval', data['interval']);
                    $('#flight-time').data('type', data['flight_type']);    // Сохраняем тип рейса - прилет или вылет
                    $('#date_start_time').prop('readonly', true);
                    if (data['flight_type'] == 'arrival')   {
                        $('#to-flight').text('К прилету рейса');
                        $('#flight-time').attr('placeholder', 'время прилета');
                    } else {
                        $('#to-flight').text('К вылету рейса');
                        $('#flight-time').attr('placeholder', 'время вылета');
                    }
                } else {
                    $('.js_flight-data').slideUp();
                    $('#date_start_time').prop('readonly', false);
                }
            }
        });
    }

    function selectNoOrYesOnShedule() {
        let day = $(this).attr('day');
        if($(this).is(':checked')) { 
            if($(this).val() == 0) {
                $(".no-display-fields").each(function() {
                    if($(this).attr('day') == day){
                        $(this).attr('hidden', ''); 
                    } 
                }); 
            } else {
                $(".no-display-fields").each(function() {
                    if($(this).attr('day') == day){
                        $(this).removeAttr('hidden'); 
                    } 
                });
            }
        } 
    }

    function copyPrices() {
        let day = $(this).attr('day');
        let price;
        $(".schedule-price").each(function() {
            if(day == $(this).attr('day')) {
                price = $(this).val();
            }
            $(this).val(price);
        });
    }

    $("#flight-time, #flight-offset").change(function () {
        if ($('#flight-time').data('type') == 'arrival') {      // Если рейс прилетает, то ко времени прибытия добавляем время сдвига
            $('#date_start_time').val(addTimes($('#flight-time').val(), $('#flight-offset').val()));
        } else {    // Если рейс вылетает, то от времени вылета отнимаем время поездки (интервал направления) и отнимаем время сдвига
            let registrTime = timeToMins($('#flight-time').val()) - $('#flight-offset').data('interval');
            let offsetMins = timeToMins($('#flight-offset').val());
            if (registrTime < offsetMins) {
                registrTime += 1440;
            }
            $('#date_start_time').val(timeFromMins(registrTime - offsetMins));
        }
    });

    function changeSchedPrice(e) {
        e.preventDefault();
        let $link = $(this);
        var dialog = bootbox.prompt({
            title: $link.data('title'),
            placeholder: "Новая цена",
            message: "<p>Внимание, будет изменена цена всех отображаемых расписаний!<br><br></p>",
            size: "large",

            callback: function (result) {
                if (result !== null && $.isNumeric(result))    {
                    $('#mass_price_update').prop('disabled', false);
                    $('#mass_price_update').val(result);
                    $('.js_table-search').submit();
                    $('#mass_price_update').prop('disabled', true);
                }
            }
        });
        return false;
    }

    // Convert a time in hh:mm format to minutes
    function timeToMins(time) {
        var b = time.split(':');
        var mins = b[0]*60 + +b[1];
        return isNaN(mins) ? 0 : mins;
    }

    // Convert minutes to a time in format hh:mm. Returned value is in range 00 to 24 hrs
    function timeFromMins(mins) {
        function z(n){return (n<10? '0':'') + n;}
        var h = (mins/60 |0) % 24;
        var m = mins % 60;
        return z(h) + ':' + z(m);
    }

    // Add two times in hh:mm format
    function addTimes(t0, t1) {
        return timeFromMins(timeToMins(t0) + timeToMins(t1));
    }

})
