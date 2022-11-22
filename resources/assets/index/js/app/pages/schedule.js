$(document).on('click', '.js_get-bus', getBus)
$(document).on('click', '.js_tour-disable-order', TourDisableOrder)
$(document).on('click', '.seat:not(.reserved)', clickSeat);
$(document).on('change', '.js_orders-count_places', clickSeat);
$(document).on('click', '.js_form-places-btn', continueOrder);
$(document).on('form-ajax', '.js_form-places', eventPlacesForm);

function TourDisableOrder() {
    $('html, body').animate({
        scrollTop: $(".blocksWrapper").offset().top
    }, 500);
    let time = $(this).data('time');
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
            $.post(url, { return_ticket: $(this).data('return'), places: $(this).data('places') }, function (response) {
                if (response.result == 'success') {
                    $wrapper.show();
                    $wrapper.addClass('active');
                    $wrapper.find('.js_get-bus-row-bus').html(response.view);
                    $wrapper.find('.js_orders-count_places').trigger('change');
                    // $wrapper.find('.js_bus-wrap').height($wrapper.find('.js_get-bus-row-bus').width() - 150)
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
    if ($('#return_flag > option:selected').val() == 1)  {
        let places = $('.scheduleBlock .js_get-bus-row-bus .cell.active:visible').length+parseInt($('.scheduleBlock .js_orders-count_places').val() || 0);  // Кол-во мест туда
        let places2 = $('.scheduleBlockReturn .js_get-bus-row-bus .cell.active:visible').length+parseInt($('.scheduleBlockReturn .js_orders-count_places').val() || 0); // Кол-во мест обратно
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
    $('.js_bus-overlay').show()
    $this = $this ? $this : $(this)
    let $wrapper = $this.closest('.js_get-bus-row');
    let $form = $wrapper.find('.js_form-places');
    let $wrapperInput = $form.find('.js_form-places-inputs')
    $wrapperInput.html('');
    $wrapperInput.prepend('<input type="hidden" name="return_ticket" value="'+$this.closest('.shedulePage').parent().find('.return-form').eq(0).val()+'"/>');
    var $countPlaces = $wrapper.find('.js_orders-count_places');

    if ($countPlaces.length) {
        let val = $countPlaces.val();
        for (let $i = 0; $i < val; $i++) {
            $wrapperInput.prepend(`<input type="hidden" name="places[]" value=""/>`);
        }
    } else {
        if (parseInt(cnt_reserved_places_tour) >= parseInt(limit_order_by_place) &&
            $(this).hasClass('active') !== true && order !== true
        ) {
            toastr.error("Бронирование ограничено");
        } else $(this).toggleClass('active');

        $wrapper.find('.seat.active:not(.reserved)').each(function () {
            let number = $(this).data('number');
            $wrapperInput.prepend(`<input type="hidden" name="places[]" value="${number}"/>`);
        })
    }
    if (order === true) $form.addClass('js_form-step-order')
    $form.submit();
    return false;
}


