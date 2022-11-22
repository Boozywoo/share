$(document).ready(function () {
    $(document).on('change', '.js_city_filter', setStreets);
    $(document).on('change', '.js_stations_from_to_price', setDataInArrows);
    $(document).on('change', '.js_stations_from_to_price', setStationFromToPrice);
    $(document).on('click', '.js_stations_price', setStationPrice);
    $(document).on('click', '.js_stations_all_price', setStationAllPrice);
    $(document).on('change', '.js_route_type', setFlights);

    $(document).delegate('.js-input-route-sales' , 'select2:selecting' , init);

    function init() {
        $(document).undelegate('.js-input-route-sales' , 'select2:selecting' , init);
        const element = $('.js-input-route-sales');
        const value = element.val();

        salesIds = (value ? value : []).reduce((result, value) => {
            result[value] = $('.js-input-route-sales option[value="' + value +'"]').data('type');

            return result;
        }, {});

        element.on('select2:select', removeSameTypeSale);
    }

    let salesIds = [];

    function setStreets() {
        var $this = $(this);
        var $streets = $('.js_street_filter');
        var $url = $streets.data('url') + '?city_id=' + $this.val();

        $('.js_street_filter option').each(function () {
            $(this).remove();
        });

        $.get($url, function (response) {
            for (var key in response) {
                $('.js_street_filter').append($('<option>', {
                    value: response[key].id,
                    text: response[key].name
                }));
            }
            $('.js_street_filter').trigger('change');
        });
    }

    function setDataInArrows() {
        let price = $(this).val();
        let station_from_id = $(this).data('station_from_id');
        let station_to_id = $(this).data('station_to_id');

        $(".arrow").each(function() {
            if(station_from_id == $(this).data('station_from_id')) {
                $(this).attr('price', price);
            }
            if(station_to_id == $(this).data('station_to_id')) {
                $(this).attr('price', price);
            }
        });
    }

    function setStationFromToPrice() {
        $.get($(this).data('url'), {
            route_id: $(this).data('route_id'),
            station_from_id: $(this).data('station_from_id'),
            station_to_id: $(this).data('station_to_id'),
            [$(this).data('type')]: $(this).val()
        }, (response) => {
            window.showNotification(response.message, response.type);
        });
    }

    function setStationPrice() {
        
        $('.js_spinner-overlay').show();
        $('.background-spinner').show();
        let price = $(this).attr('price');
        let station_from_id = null, station_to_id = null;
        $(".arrow").each(function() {
            if(price == $(this).attr('price')) {
                if($(this).data('station_from_id') != null) {
                    station_from_id = $(this).data('station_from_id');
                } else {
                    station_to_id = $(this).data('station_to_id');
                }    
            }
        });

        $.get($(this).data('url'), {
            route_id: $(this).data('route_id'),
            price: $(this).attr('price'),
            station_from_id: station_from_id,
            station_to_id: station_to_id
        }, (response) => {
            $('.js_spinner-overlay').hide();
            $('.background-spinner').hide();
            window.showNotification(response.message, response.type);
            location.reload();
        });
    }

    function setStationAllPrice() {
        
        $('.js_spinner-overlay').show();
        $('.background-spinner').show();

        $.get($('#all-sells').data('url'), {
            route_id: $('#all-sells').data('route_id'),
            price: $('#all-sells').val(),
        }, (response) => {
            $('.js_spinner-overlay').hide();
            $('.background-spinner').hide();
            window.showNotification(response.message, response.type);
            location.reload();
        });
    }

    function setFlights() {
        if (this.value == 'is_transfer') {
            $('.js_flight_type').slideDown();
        } else {
            $('.js_flight_type').slideUp();
        }
    }

    function removeSameTypeSale() {
        const values = $(this).val();
        const ids = Object.keys(salesIds);

        const newValue = values.filter((value) => ids.indexOf(value) === -1).shift();
        const type = $('.js-input-route-sales option[value="' + newValue +'"]').data('type');

        salesIds = ids.reduce((result, value) => {
            if (salesIds[value] === type && type === 'each') {
                return result;
            }
            result[value] = salesIds[value];

            return result;
        }, {});


        salesIds[newValue] = type;
        $(this).val(Object.keys(salesIds)).change();
    }

    $('#popup_route-edit').on('show.bs.modal', function (e) {
        let $button = $(e.relatedTarget)
        let url = $button.data('url')
        $.get(url, (response) => {
            $(this
            ).find('.modal-content').html(response.html)
            window.init()
        })
    });

    $(document).on('hidden.bs.modal', function (e) {
        $(this).find('.modal-content').html('')
    });
});
