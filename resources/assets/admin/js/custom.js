$(document).ready(function () {

    let alertOpen = false;

    $(document)
        .on('change', '.js_table-search :input', searchTable)
        // .on('select', '.js_table-search select', searchTable)
        // .on('select2:select', '.js_table-search select', searchTable)
        .on('submit', '.js_table-search', searchTable)
        .on('change', '#hide_filter', changeFilterMode)
        .on('click', '.js_table-pagination a', paginateTable)
        .on('click', '.btn-filter-submit', searchTableSubmit)
        .on('click', '.js_table-reset', resetSearchTable)
        .on('panel-form-ajax-success', '.js_form-ajax', resetAjaxForm)
        .on('click', '#side-menu .pjax-link', activeMenu)
        .on('click', '#side-menu .pjax-link', activeMenu);

    $(document).pjax('.pjax-link', '#pjax-container', {fragment: '#pjax-container', timeout: 20000});


    // $('#side-menu').on('click', function() {
    //     if ($(window).width() < 768) {
    //         $('.fixed-sidebar').toggleClass('mini-navbar');
    //     }

    // });

    function activeMenu() {
        $('#side-menu li').not('.sub-menu').removeClass('active');
        $(this).closest('li').addClass('active');
    }

    $('#pjax-container').on('pjax:beforeSend', () => {
        $('.wrapper-spinner').show();
    })

    $('#pjax-container').on('pjax:complete', () => {
        $('.wrapper-spinner').hide();
        window.initSortable();
        window.map();
        window.initSortableStation();
        window.initOrderClientPhone();
        window.initTemplateBus();
        $('[data-toggle="tooltip"]').tooltip();
        init();
    })

    function resetAjaxForm(e, response) {
        let $back = $('.js_form-ajax-back');
        if (response.redirect_url) $back.attr('href', response.redirect_url)
        if ($(this).hasClass('js_form-ajax-reset') || response.redirect_url) {
            setTimeout(() => $back.click(), 500);
        }
    }

    function searchTable(e) {
        e.preventDefault();
        $('.wrapper-spinner').show();
        let $form = $('.js_table-search');
        history.pushState({}, '', '?' + $form.serialize());
        $form.ajaxSubmit({
            success: function (data) {
                $('.wrapper-spinner').hide();
                renderData(data);
            }
        });
        return false;
    }

    function searchTableSubmit(e, hide_filter = false) {
        e.preventDefault();
        $('.wrapper-spinner').show();
        let $form = $('.js_table-submit');

        // let condition_str = e.target.checked ? $form.serialize() + "&" + e.target.name + "=1" : $form.serialize();
        // history.pushState({}, '', '?' + condition_str);
        let data = serializeObject($form.serializeArray());
        if (hide_filter) {
            data['hide_filter'] = 1;
        }
        console.log(data);
        let link = $form.data("link");
        $(".close").trigger("click");
        $.ajax({
            type: "POST",
            url: link,
            data: data, // serializes the form's elements.
            success: function (data) {
                $('.wrapper-spinner').hide();
                renderData(data);
            }
        });
        return false;
    }

    function serializeObject(serializeArray) {
        console.log(serializeArray);
        var result = {};
        result['fields'] = [];

        serializeArray.map(function (item) {
            console.log(item['name']);
            if (item['name'] == "fields[]") {
                result['fields'].push(item['value']);
            } else {
                result[item['name']] = item['value'];
            }
        })
        return result;
    }

    function paginateTable(e) {
        e.preventDefault();
        let link = $(this).attr('href');
        $('#js-current-page').val($(this).text());
        $('.wrapper-spinner').show();
        $.get(link, function (data) {
            $('.wrapper-spinner').hide();
            renderData(data);
            $.scrollTo($('.ibox-content'), 400);
        });
        return false;
    }

    function renderData(data) {
        let $table = $('.js_table-wrapper');
        let $pagination = $('.js_table-pagination');
        let $filter = $('.filter-data');
        $table.html(data.view);
        $pagination.html(data.pagination);
        data.filter ? $filter.html(data.filter) : '';
        $('[data-toggle="tooltip"]').tooltip();

        $('.packages-button').addClass('packages-button-active').css('display', 'inline')
        $('.tours-button').removeClass('tours-button-active').css('display', 'none')
    }

    function resetSearchTable(e) {
        e.preventDefault();
        let $form = $('.js_table-search');
        $form.find('input').not('.js_table-reset-no').val('');
        $form.find('textarea').val('');
        $form.find('select').prop('selectedIndex', 0);
        searchTable(e);
        return false;
    }


    function init() {

        $(".js_input-select2").select2({
            allowClear: true
        });

        $('.time-mask').inputmask({
            alias: "h:s"
        })

        $('.js_datepicker').datepicker({
            format: 'dd.mm.yyyy',
            autoclose: true,
            todayHighlight: true,
            language: 'ru',
            dateFormat: 'dd.mm.yyyy',
            changeMonth: true,
            changeYear: true,
            startDate: '-1200m'
        }).on('changeDate', function (ev) {
            if ($(this).hasClass('js_table-reset-no')) $(this).trigger('change');
            if ($(this).data('date')) {
                let $date = $('[name=date]');
                $date.val($('.js_datepicker').datepicker('getFormattedDate'));
                $date.trigger('change');
            }
        });

        $('.js_datepicker2').datepicker({
            format: 'dd.mm.yyyy',
            autoclose: true,
            todayHighlight: true,
            language: 'ru',
            dateFormat: 'dd.mm.yyyy',
            changeMonth: true,
            changeYear: true,
            startDate: '-1200m'
        }).on('changeDate', function (ev) {
            if ($(this).hasClass('js_table-reset-no')) $(this).trigger('change');
            if ($(this).data('date')) {
                let $date = $('[name=date]');
                $date.val($('.js_datepicker').datepicker('getFormattedDate'));
                $date.trigger('change');
            }
        });

        $('.js_datepicker_without_previous').datepicker({
            format: 'dd.mm.yyyy',
            autoclose: true,
            todayHighlight: true,
            language: 'ru',
            dateFormat: 'dd.mm.yyyy',
            changeMonth: true,
            changeYear: true,
            // startDate: '-1200m',
            minDate: 0,
            startDate: new Date(),
        }).on('changeDate', function (ev) {
            if ($(this).hasClass('js_table-reset-no')) $(this).trigger('change');
            if ($(this).data('date')) {
                let $date = $('[name=date]');
                $date.val($('.js_datepicker_without_previous').datepicker('getFormattedDate'));
                $date.trigger('change');
            }
        });

        $('.input-daterange').datepicker({
            format: 'yyyy-mm-dd',
            keyboardNavigation: false,
            forceParse: true,
            toggleActive: false,
            language: 'ru',
            autoclose: true,
        }).on('changeDate', function (ev) {
            $(this).trigger('input');
        });

        $('#is_egis-yes').on('click', function () {
            if (confirm('Установить обязательные поля для заполнения в соответствии с требованиями ЕГИС?')) {
                $(".js_input-select2").val(['first_name', 'last_name', 'middle_name', 'doc_type', 'doc_number', 'phone', 'birth_day', 'gender', 'country_id']).trigger("change");
            }
        });

        $(".js-select-search-tours").each(function(){
            var $this = $(this);
            $this.select2({
                width: "100%",
            }).on('select2:unselecting', function() {
                $(this).data('unselecting', true);
            }).on('select2:opening', function(e) {
                if ($(this).data('unselecting')) {
                    $(this).removeData('unselecting');
                    e.preventDefault();
                }
            });
        });

        $(".js-select-company").on('change', function() {
            if ('URLSearchParams' in window) {
                var searchParams = new URLSearchParams(window.location.search);
                searchParams.set("company", $(this).find(":selected").val());
                searchParams.set("driver_id", '');
                window.location.search = searchParams.toString();
            }
        });

        /*$(".js_orders-count_places").mouseleave(function () {
            if (!$('.box').hasClass('.js_div_order_places')) {
                $(".js_order_calculation").click();
            }
        });*/

        //checkAllowIp();
    }

    init();

    window.init = init;
    $(document).on('pjax:beforeSend', checkOrder);

    function checkOrder() {
        if ($('.js_orders-id').val() && $('.js_orders-type').val() === 'no_completed' && !alertOpen) {
            alertOpen = true;
            if (confirm("Бронь будет удалена. Покинуть страницу?")) {
                $('.js_order-cancel').click();
                $(document).one('pjax:success', function () {
                    alertOpen = false;
                });
                return true;
            } else {
                $('.wrapper-spinner').hide();
                alertOpen = false;
                return false;
            }
        }
    }


    /*function checkAllowIp() {
        if (SETTING.allowed_ip && SETTING.allowed_ip.length && SETTING.allowed_ip.indexOf(SETTING.CURRENT_IP) < 0 && SETTING.IS_AUTH === '1' && SETTING.IS_OPERATOR === '1') {
            if (window.location.pathname !== '/admin') {
                window.location.replace("/admin");
                alert('Доступ к сайту запрещён с вашего IP-адреса.');
            }
        }
    }*/


    $(document).on('click', '.onoffswitch-checkbox-panel', function () {
        var placeId = $(this).data('place_id');
        console.log(placeId);
        var elPrice = "#order_place_price_" + placeId;
        var elSpan = "#order_place_span_" + placeId;
        if ($(elPrice).is(":visible")) {
            $(elPrice).hide();
            $(elSpan).show();
        } else {
            $(elPrice).show();
            $(elSpan).hide();
        }
        return true;
    });

    $(document).on('click', '.onoffswitch-checkbox', function () {
        var placeId = $(this).data('place_id');
        console.log(placeId);
        var elPrice = "#order_place_price_" + placeId;
        var elSpan = "#order_place_span_" + placeId;
        if ($(elPrice).is(":visible")) {
            $(elPrice).hide();
            $(elSpan).show();
        } else {
            $(elPrice).show();
            $(elSpan).hide();
        }
        //$("#is_handler_price_" + placeId).click();

        var order_id = $('.js_div_order_places').data('order_id');
        var url = $('.js_div_order_places').data('url');
        var data = $('.js_div_order_places :input').serializeArray();
        $.get(url, data);

        return true;
    });

    function changeFilterMode(e) {
        searchTableSubmit(event, true);
        // e.preventDefault();
        /*
                $('.wrapper-spinner').show();
                let $form = $('.js_table-submit');
                let condition_str = e.target.checked ? $form.serialize() + "&" + e.target.name + "=1" : $form.serialize();


                history.pushState({}, '', '?' + condition_str);
                $(".close").trigger("click");
                $.ajax({
                    type: "GET",
                    url: location.href,
                    data: condition_str, // serializes the form's elements.
                    success: function (data) {
                        $('.wrapper-spinner').hide();
                        renderData(data);
                    }
                });
        */
    }
});
