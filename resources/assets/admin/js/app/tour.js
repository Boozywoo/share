$(document).ready(function () {

    $(document).on('click', '.js_tour-edit-calculation', tourCalculation);
    $(document).on('click', '.js_tour-edit-forced', tourEditForced);
    $(document).on('click', '.js_input_is_call', OrderInputIsCall);
    $(document).on('click', '.js_admin_send_actual_sms', OrderSendActualSms);
    $(document).on('click', '.js_admin_call', CallClient);
    $(document).on('click', '#print_page', PrintPageTour);
    $(document).on('click', '#print_doc', PrintDocTour);
    $(document).on('click', '#print_page_reverse', PrintPageTourReverse);
    $(document).on('change', '.station_from_time', setTimeFrom);
    $(document).on('change', '.js_city_from_id', CityFrom);
    $(document).on('focusout', '.js_input_order_price', ChangePrice);
    $(document).on('change', '.js_all_dates', allDates);
    $(document).on('change', '.js_only_visible', onlyVisible);
    $(document).on('click', '.js_mass_change_price', massChangePrice);
    $(document).on('click', '.js_copy_to_clipboard', copyToClipboard);

    function ChangePrice() {
        let id = $(this).data('order_id');
        let url = $(this).data('url');
        let price = $(this).val();

        $.get(url + '?id=' + id + '&price=' + price, (response) => {
            console.log(response);
            if (response.message != '') toastr.success(response.message);
        });
    }

    function CityFrom() {
        let url = $(this).data().url;
        let value = $(this).val();
        $(".js_city_to_id option").remove();
        $('.js_city_to_id').prop('disabled', false);

        $.get(url + '?city_from_id=' + value, (response) => {
            $('.js_city_to_id').append($('<option>', {
                value: 0,
                text: '-Куда-'
            }));

            for (var key in response) {
                $('.js_city_to_id').append($('<option>', {
                    value: response[key].id,
                    text: response[key].name
                }));
            }
        })
        ;
    }

    function CallClient() {
        $.get($(this).data('url') + '?phone=' + $(this).data('phone'), (response) => {
            var Type = response.type;
            if (response.type === 'error') {
                toastr.error(toastr.error);
            } else {
                toastr.success('звонок сгенерирован');
            }
        });
    }

    function OrderSendActualSms() {
        let order_id = $(this).data('id');
        let url = $(this).data('url');
        let count_sms = $('.js_order_row_' + order_id).find('.js_count_sms').html();

        count_sms = parseInt(count_sms) + 1;
        $('.js_order_row_' + order_id).find('.js_count_sms').text(count_sms);
        $.get(url + '?id=' + order_id, (response) => {
            toastr.success('смс отправлена');
        });
    }


    function setTimeFrom() {
        let url = $(this).data('url');
        let id = $(this).data('id');
        let station_from_time = $(this).val();

        $.get(url + '?id=' + id + '&station_from_time=' + station_from_time, (response) => {
            if (response.result == 'success') {
                toastr.success(response.message);
            } else {
                toastr.error('что-то пошло не так');
            }
        });
    }

    function dump(obj) {
        var out = '';
        for (var i in obj) {
            out += i + ": " + obj[i] + "\n";
        }

        alert(out);

        // or, if you wanted to avoid alerts...

        var pre = document.createElement('pre');
        pre.innerHTML = out;
        document.body.appendChild(pre)
    }

    function OrderInputIsCall() {

        let value = 0;
        let id = $(this).attr('id');
        let url = $(this).attr('url');
        let phone = $(this).attr('phone');
        if ($(this).is(':checked')) value = 1;
        $.get(url + '?id=' + id + '&is_call=' + value + '&phone=' + phone, (response) => {
            if (response.result == 'success') {
                toastr.success(response.message);
            } else {
                toastr.error('что-то пошло не так(');
            }
        });
    }

    function tourCalculation() {
        let $calculation = $('[name=calculation]');
        $calculation.val(1);
        $('.js_tours-from').submit()
        $calculation.val(0);
    }

    function tourEditForced() {
        $('.js_tours-from').append('<input type="hidden" name="action" value="forceEdit" />');
        $('.js_tours-from').submit();
        $('input[value=forceEdit]').remove();
    }

    $('#popup_tour-edit').on('show.bs.modal', function (e) {
        let $button = $(e.relatedTarget)
        let url = $button.data('url')
        $.get(url, (response) => {
            $(this
            ).find('.modal-content').html(response.html)
            window.init()
        })
    })

    $('#popup_rent-edit').on('show.bs.modal', function (e) {
        let $button = $(e.relatedTarget)
        let url = $button.data('url')
        $.get(url, (response) => {
            $(this
            ).find('.modal-content').html(response.html);
            $('.js-select-search-single').select2({
                width: "100%",
            });
            window.init()
        })
    })

    $(document).on('hidden.bs.modal', function (e) {
        $(this).find('.modal-content').html('')
    });

    function PrintPageTour() {
        var printWindow = window.open(window.location.href + '/print');
        printWindow.addEventListener('load', function () {
            printWindow.print();
            // printWindow.close();
        }, true);

    }

    function PrintDocTour() {
        var printWindow = window.open(window.location.href + '/doc/print');
        printWindow.addEventListener('load', function () {
            printWindow.print();
            // printWindow.close();
        }, true);
    }

    function PrintPageTourReverse() {
        var printWindow = window.open(window.location.href + '/print/reverse');
        printWindow.addEventListener('load', function () {
            printWindow.print();
            // printWindow.close();
        }, true);

    }

    function countPull() {
        $.get('/admin/pulls/count', (response) => {
            $('.js_pull-count').html(response.view);
        })
    }

    function countNotification() {
        $.get('/admin/notifications/count', function (response) {
            $('.js_noti-count').html(response.view);
        });
    }

    function allDates() {
        $('#all_dates').val($(this).prop('checked') ? 1 : 0);
        $('#all_dates').prop('disabled', $(this).prop('checked') ? false : true);
        $('.js_table-search').submit();
    }

    function onlyVisible() {
        $('#only_visible').val($(this).prop('checked') ? 1 : 0);
        $('#only_visible').prop('disabled', $(this).prop('checked') ? false : true);
        $('.js_table-search').submit();
    }

    function massChangePrice(e) {
        e.preventDefault();
        let $link = $(this);
        var dialog = bootbox.prompt({
            title: $link.data('title'),
            placeholder: "Новая цена",
            message: "<p>Внимание, будет изменена цена всех отображаемых рейсов!<br><br></p>",
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

    function copyToClipboard(e) {
        e.preventDefault();
        let copyText = $(this).data('text');
        navigator.clipboard.writeText(copyText).then(function() {
            toastr.success('Ссылка успешно скопирована');
        }, function(err) {
            toastr.success('Ссылка не скопирована');
        });

    }

    countPull();
    countNotification();

    setInterval(function () {
        return countPull();
    }, 100000);

    window.countPull = countPull;
    window.countNotification = countNotification;

})
