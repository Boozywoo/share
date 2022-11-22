$(document).ready(function () {

    $(document).on('panel-form-ajax-success', '.js_orders-from', eventOrdersForm);
    $(document).on('panel-form-ajax-error', '.js_orders-from', eventOrdersForm);
    $(document).on('click', '.js_orders-client-search', getClientInfo);
    $(document).on('click', '.js_orders-toTour', toTour);
    $(document).on('click', '.seat:not(.reserved)', toggleSeat);
    $(document).on('change', '.js_orders-count_places', updateFormSeatActive);
    $(document).on('click', '.js_orders-to-tours', toTours);
    $(document).on('click', '.js_orders-get-check', getCheck);
    $(document).on('click', '.js_orders-completed', orderCompletedType);
    $(document).on('click', '.js_orders-completed_continue', orderCompletedContinueType);
    $(document).on('click', '.js_orders-completed_return', orderCompletedReturnType);
    $(document).on('click', '.js_order_calculation', orderCalculation);
    $(document).on('click', '.js_order-cancel', orderCancel);
    $(document).on('click', '.js_orders-selection-places', orderSelectionPlaces);
    $(document).on('change', '.js_admin_orders-count_places_child', setChild);
    $(document).on('change', '.js_set_station_from', setNewStation);
    $(document).on('change', '.js_station_to_filter', ChangeToStation);
    $(document).on('change', '.js_orders-client-status_is', setNewStatus);
    $(document).on('change', '#country', maskPhone);
    $(document).on('click', '.save_order_places', SaveOrderPlaces);
    $(document).on('click', '.save_order', SaveOrder);
    $(document).on('focusout', '.js_input_order_places', SaveOrderPlacesData);
    $(document).on('change', '.js_datepicker_order_places', SaveOrderPlacesData);

    if (!$('#phone').val()) {
        $("#station_from_id option:not(:selected), #station_to_id option:not(:selected)").prop('disabled', true);
    } else {
        $("#station_from_id, #station_to_id").tooltip('disable');
    }

    function orderCalculation() {
        $('.js_orders-type').val('no_completed');
    }

    function ChangeToStation() {
        $('.wrapper-spinner').show();
        checkStation('to');
    }

    function checkStation(destination) {
        var data = {
            tour_id: $("input[name='tour_id']").val(),
            station_from_id: $("#station_from_id").val(),
            station_to_id: $("#station_to_id").val(),
            count_places: $("#count_places").val(),
            order_id: $('.js_orders-id').val(),
            order_slug: $('.js_orders-slug').val(),
            status: $('#status').val(),
            destination: destination,
        };

        $.get('/admin/orders/check_stations', data, (response) => {
            $('.wrapper-spinner').hide();
            if (response.result === 'error') {
                $('.ibox-footer').hide();
                toastr.error(response.message);
            } else {
                $('#BusLayout').html(response.bus_tour);

                if ($('.js_orders-client-phone').inputmask("isComplete")) {
                    $(".js_order_calculation").parent().parent().submit();
                    $('.ibox-footer').show();
                    toastr.success(response.message);
                }
            }
        });
    }

    function getCheck() {
        let url = $(this).data('url');

        $.get(`${url}`, (response) => {
            if (response.result == 'success') {
                toastr.success(response.message); 
                window.open(response.link, '_blank').focus();
            } else {
                toastr.error(response.message);
            }
        })
    }

    function SaveOrderPlaces() {
        let order_id = $(this).data('order_id');
        let el = '#order_places-' + order_id;
        let url = $(this).data('url');
        let data = $(el + ' :input').serializeArray();
        $.post(url, data, function () {
            $('a[data-target="#order-' + order_id + '"]').click();
            toastr.success('Данные сохранены!');
        });
    }

    function SaveOrder() {
        let order_id = $(this).data('order_id');
        let el = '#' + order_id;
        let url = $(this).data('url');
        let data = $(el + ' :input').serializeArray();
        $.post(url, data, function () {
            $('a[data-target="#order_places-' + order_id + '"]').click();
            toastr.success('Данные сохранены!');
        });
    }

    function SaveOrderPlacesData() {
        let order_id = $('.js_div_order_places').data('order_id');
        let url = $('.js_div_order_places').data('url');
        let data = $('.js_div_order_places :input').serializeArray();
        $.post(url, data);
    }

    function setNewStatus() {
        if (confirm("Вы подтверждаете изменение?")) {
            const status_id = $(this).val();
            const url = $(this).data('url');
            const id = $('input[name=client_id]').val();
            $(this).data("current", $(this).val());

            if (id) {
                $('.wrapper-spinner').show();
                $.get(url + '?id=' + id + '&status_id=' + status_id, (response) => {
                    $('.wrapper-spinner').hide();
                    $(".js_order_calculation").click();
                    toastr.success('Социальный статус успешно обновлён');
                })
            } else  {
                toastr.warning('Клиент не загружен');
            }

            return true;
        } else {
            $(this).val($(this).data('current'));

            return false;
        }
    }

    function setNewStation() {
        let $this = $(this);
        let url = $this.data('url');
        let route_id = $this.data('route_id');
        let station_from_id = $this.val();
        let station_to_id = $this.data('station_to_id');
        $('.wrapper-spinner').show();
        $.get(url + '?route_id=' + route_id + '&station_from_id=' + station_from_id + '&station_to_id=' + station_to_id, (response) => {
            $('.js_set_station_to').html(response);
            checkStation('from');
        })
    }

    function orderSelectionPlaces() {
        let $this = $(this)
        let url = $this.data('url');
        $.get(url, (response) => {
            if (response.result == 'success') {
                $('.js_orders-left').html(response.html)
                $('.js_orders-places_with_number').val(1)
                $this.remove()
                updateFormSeatActive()
            }
        })
        return false;
    }

    function setChild() {
        let url = $(this).data('url');
        let order_id = $(this).data('order_id');

        let $prices = $('.js_admin_price_places');
        let count = $(this).val();
        $('.js_input_order_places').first().trigger('focusout');
        $.get(`${url}?count=${count}&order_id=${order_id}`, (response) => {
            if (response.result == 'success') {
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
            $prices.html(response.view);
        })
    }

    function orderCancel() {
        let url = $(this).attr('href') + '?id=' + $(this).data('id');
        $.get(url, (response) => {
            if (response.result == 'success') {
                window.showNotification(response.message, 'error')
                $('.js_form-ajax-back').click()
            }
        })
        return false;
    }

    function orderCompletedType() {
        $(this).addClass('click');
        let $type = $('.js_orders-type');
        // let type = $type.val();
        $type.val($(this).data('type'))
        $('.js_orders-from').submit()
        // setTimeout(function () {
        // //     let orderId = $('.js_orders-slug').val();
        //     // toastr.success('Номер брони ' + orderId);
        // }, 2000);
        // $type.val(type)
    }

    function orderCompletedContinueType() {
        if ($('.js_orders-client-phone').val().length > 10) {
            let location_href = $(this).data('url') + '?incomming_phone=' + $('.js_orders-client-phone').val();
            $(this).addClass('click');
            let $type = $('.js_orders-type');
            // let type = $type.val();
            $type.val($(this).data('type'))
            $('.js_orders-from').ajaxSubmit({
                success: function success(data) {
                    if (data.id !== undefined) {
                        window.location.href = location_href;
                    } else {
                        toastr.error('Не заполнены все обязательные поля!');
                    }
                }
            });
        } else toastr.error('Не заполнен номер телефона');
    }

    function orderCompletedReturnType() {
        if ($('.js_orders-client-phone').val().length > 10) {
            var location_href = $(this).data('url') + '?incomming_phone=' + $('.js_orders-client-phone').val();
            $(this).addClass('click');
            var $type = $('.js_orders-type');
            $type.val($(this).data('type'));

            $('.js_orders-from').ajaxSubmit({
                success: function success(data) {
                    if (data.id !== undefined) {
                        window.location.href = location_href + '&order_return=' + data.id;
                    } else {
                        toastr.error('Не заполнены все обязательные поля!');
                    }
                }
            });

        } else toastr.error('Не заполнен номер телефона');

    }

    function toggleSeat() {
        let phone = $('.js_orders-client-phone').val();
        let firstName = $('.js_orders-client-first_name').val();
        if (phone.replace(/\D/g, '').length > 10 && firstName) {
            $(this).toggleClass('active')
            updateFormSeatActive()
            //$('.js_orders-from').submit()
        } else {
            window.showNotification('Введите сперва номер телефона и имя', 'error');
        }
    }

    function eventOrdersForm(e, response) {
        if (response.result == 'success' && $('.js_orders-type').val() == $('.js_orders-completed').data('type') && $('.js_orders-completed').hasClass('click')) {
            setTimeout(() => $('.js_form-ajax-back').click(), 500);
        } else {
            $('.js_orders-completed').removeClass('click')
        }

        // console.log(response);

        if (response.id) {
            $('.js_orders-slug').val(response.slug);
            $('.js_orders-id').val(response.id);

            // hm....
            toastr.success('Номер брони ' + response.slug);

            if (!$('.js_order-old-places').length) {
                $('.js_order-cancel').data('id', response.id).removeClass('hidden')
            }
        }
        if (response.view_tour) {
            $('.js_orders-left').html(response.view_tour);
            $('.js_input_order_places').first().trigger('focusout');
        }
        if (response.result == 'error') updateFormSeatActive()

    }

    function toTours() {
        let url = $(this).data('url');
        $.get(url, (response) => {
            if (response.result == 'success') {
                $('.js_orders-left').html(response.html)
                $('.js_orders-filter').html(response.filter)
                window.init()
            }
        })
    }

    function toTour() {
        let phone = $('input[name=phone]').val();
        let city_from_id = $(this).data('city_from_id');
        let city_to_id = $(this).data('city_to_id');
        let url = $(this).data('url') + '?phone=' + phone + '&city_from_id=' + city_from_id + '&city_to_id=' + city_to_id + '&with_phone=' + Number(phone.length === 0);
        $.get(url, (response) => {
            if (response.result == 'success') {
                $('.js_orders-filter').html('')
                $('.js_orders-left').html(response.html)
                $('.js_orders-tour-info').html(response.tour_info)
                $('.js_orders-client-info').html(response.viewClientInfo)
                $('.js_orders-tour_id').val(response.tour_id)
                updateFormSeatActive();

                if (response.clientPhone) {
                    $('#country').val(response.clientPhone);
                    switchCountry(response.clientPhone);
                }
            }
        })
    }

    function maskPhone() {
        var country = $('#country option:selected').val();
        switchCountry(country);
    }

    function switchCountry(country) {
        switch (country) {
            case "ru":
                $("#phone").inputmask("+7(999) 999-99-99", {'oncomplete': getClientInfo});
                break;
            case "ua":
                $("#phone").inputmask("+380(99) 999-99-99", {'oncomplete': getClientInfo});
                break;
            case "by":
                $("#phone").inputmask("+375(99) 999-99-99", {'oncomplete': getClientInfo});
                break;
            case "de":
                $("#phone").inputmask("+4\\9(999) 9999-999", {'oncomplete': getClientInfo});
                break;
            case "dee":
                $("#phone").inputmask("+4\\9(999) 999-99-999", {'oncomplete': getClientInfo});
                break;
            case "cz":
                $("#phone").inputmask("+420(999) 999-999", {'oncomplete': getClientInfo});
                break;
            case "il":
                $("#phone").inputmask("+\\972(99) 999-9999", {'oncomplete': getClientInfo});
                break;
            case "us":
                $("#phone").inputmask("+1(999) 999-9999", {'oncomplete': getClientInfo});
                break;
            case "fi":
                $("#phone").inputmask("+358(99) 999-999", {'oncomplete': getClientInfo});
                break;
            case "no":
                $("#phone").inputmask("+47(99) 999-999", {'oncomplete': getClientInfo});
                break;
            case "pl":
                $("#phone").inputmask("+48(999) 999-999", {'oncomplete': getClientInfo});
                break;
            case "uz":
                $("#phone").inputmask("+\\9\\98(99) 999-99-99", {'oncomplete': getClientInfo});
                break;
            case "tm":
                $("#phone").inputmask("+\\9\\93(999) 999-999", {'oncomplete': getClientInfo});
                break;
            case "md":
                $("#phone").inputmask("+373(99) 999-999", {'oncomplete': getClientInfo});
                break;
            case "az":
                $("#phone").inputmask("+\\9\\94(99) 999-99-99", {'oncomplete': getClientInfo});
                break;
            case "tj":
                $("#phone").inputmask("+\\9\\92(9999) 9-99-99", {'oncomplete': getClientInfo});
                break;
            case "fr":
                $("#phone").inputmask("+33(999) 999-999", {'oncomplete': getClientInfo});
                break;
            case "gr":
                $("#phone").inputmask("+30(999) 999-99-99", {'oncomplete': getClientInfo});
                break;
        }
    }

    maskPhone();

    function getClientInfo() {
        let tour_id = $('input[name=tour_id]').val();
        let $this = $('.js_orders-client-phone');
        let url = $this.data('url-client-info');
        let phone = $this.val();
        $.get(`${url}`, {'phone': phone, 'tour_id': tour_id}, (response) => {
            $('.js_orders-client-info').html(response.viewClientInfo);
            $('.js_orders-client-status_is').val(response.status_id);
            $('.js_orders-client-date_social').val(response.date_social);
            $('.js_transfer_street').val(response.last_address.street);
            $('.js_transfer_house').val(response.last_address.house);
            $('.js_transfer_building').val(response.last_address.building);
            $('.js_transfer_apart').val(response.last_address.apart);
            $('.js-taxi-history').on('change', function () {
                $('#suggest_from').val($(this).find(':selected').data('from'));
                $('#suggest_to').val($(this).find(':selected').data('to'));
            });
            $("#station_from_id option:not(:selected), #station_to_id option:not(:selected)").prop('disabled', false);
            $("#station_from_id, #station_to_id").tooltip('disable');
            if ($("#station_from_id").val()) {
                ChangeToStation()
            }
            init();
            window.showNotification(response.message, response.type);
        })
    }

    function initOrderClientPhone() {
        if ($('#country').val()) {
            switchCountry($('#country').val());
        } else {
            $("#country").val(InputCodeValue);
            switchCountry(InputCodeValue);
        }
    }

    function updateFormSeatActive() {
        let $wrapper = $('.js_orders-places-input');
        $wrapper.html('');
        let $countPlaces = $('.js_orders-count_places');
        if ($countPlaces.length) {
            let val = $countPlaces.val();
            for (let $i = 0; $i < val; $i++) {
                $wrapper.prepend(`<input type="hidden" name="places[]" value="" data-number=""/>`);
            }
        } else {
            $('.seat.active:not(.reserved)').each(function () {
                let number = $(this).data('number');
                $wrapper.prepend(`<input type="hidden" name="places[]" value="${number}" data-number="${number}"/>`);
            })
        }
    }

    initOrderClientPhone();
    window.initOrderClientPhone = initOrderClientPhone;

});
