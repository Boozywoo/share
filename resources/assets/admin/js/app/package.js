$(document).ready(function () {

    $('#popup_package-add').on('show.bs.modal', function (e) {
        let $button = $(e.relatedTarget)
        let url = $button.data('url')
        $.get(url, (response) => {
            $(this).find('.modal-content').html(response.html)
            window.init()
        })
    })

    $('#popup_packages_of_tour').on('show.bs.modal', function (e) {
        let $button = $(e.relatedTarget)
        let url = $button.data('url')
        $.get(url, (response) => {
            $(this).find('.modal-content').html(response.html)
            window.init()
        })
    })

    $(document).on('changeDate', '#time_start', ChangeDate);
    $(document).on('change', '#route_id', ChangeRoute);
    $(document).on('click', '#index-packages', IndexPackagesByDate);
    $(document).on('click', 'input[name="station_radio"]', stationRadio);

    var url_cal_glob

    function ChangeDate() {
        let time_start
        time_start = $('[name=time_start]').val();
        url_cal_glob = "".concat($(this).data('url'), "/").concat(time_start)
        $('.from_dist_package').css('display', 'none');
        $('#tour_id').prop('disabled', true);
        $.get(url_cal_glob, (response) => {
            //tours
            $('#tour_id').empty();
            $('#tour_id').append('<option value="">' + 'Выберите рейс' + '</option>');
            $.each(response.tours, function (key, value) {
                $('#tour_id').append('<option value="' + value.id + '">' + value.start + ', ' + value.route_name + ', ' + value.bus_name + ', ' + value.driver_name + '</option>');
            });
            //routes
            $('#route_id').empty();
            $('#route_id').append('<option value="">выберите направление</option>');
            let routes = [...new Map(response.tours.map(item => [item['route_id'], item])).values()];
            $.each(routes, function (key, value) {
                $('#route_id').append('<option value="' + value.route_id + '">' + value.route_name + '</option>');
            });
            $('#route_id').prop('disabled', false);
        })
    }

    function ChangeRoute() {
        $('#package_from').val('')
        $('#package_destination').val('')
        $('#from_station_id').val('')
        $('#destination_station_id').val('')

        $('.from_dist_package_field').css('display', 'none');
        $('input[name="station_radio"]:first').prop('checked', true);
        if ($('#route_id').val() != '') {
            $('#tour_id').prop('disabled', false);
            $('.from_dist_package').css('display', 'block');
            $('.from_dist_package_select').css('display', 'block');
        } else {
            $('#tour_id').append('<option value="">выберите рейс</option>');
            $('#tour_id').prop('disabled', true);
            $('.from_dist_package').css('display', 'none');
            $('.from_dist_package_field').css('display', 'none');
            $('.from_dist_package_select').css('display', 'none');
        }

        let url_cal_route = url_cal_glob + "/" + $(this).children('option:selected').val()
        $.get(url_cal_route, (response) => {
            $('#tour_id').empty();
            $('#from_station_id').empty();
            $('#destination_station_id').empty();

            $.each(response.tours, function (key, value) {
                $('#tour_id').append('<option value="' + value.id + '">' + value.start + ', ' + value.route_name + ', ' + value.bus_name + ', ' + value.driver_name + '</option>');
            });

            //$('#from_station_id').append('<option value="">выберите отстановку</option>');
            $.each(response.stations.stations, function (key, value) {
                $('#from_station_id').append('<option value="' + value.id + '">' + value.name + '</option>');
            });

            //$('#destination_station_id').append('<option value="">выберите отстановку</option>');
            $.each(response.stations.stations, function (key, value) {
                $('#destination_station_id').append('<option value="' + value.id + '">' + value.name + '</option>');
            });
        })

        url_cal_glob_route = null
    }

    function stationRadio() {
        let radioValue = $(this).val()

        $('#package_from').val('')
        $('#package_destination').val('')
        $('#from_station_id').val('')
        $('#destination_station_id').val('')

        if (radioValue == 0) {
            $('.from_dist_package_select').css('display', 'none');
            $('.from_dist_package_field').css('display', 'block');
        } else if (radioValue == 1) {
            $('.from_dist_package_select').css('display', 'block');
            $('.from_dist_package_field').css('display', 'none');
        }
    }

    function IndexPackagesByDate() {
        let packagesBtn = $('.packages-button')
        let toursBtn = $('.tours-button')
        let toursIndex = $('#tours-index')
        let packagesIndex = $('#packages-index')

        if (packagesBtn.hasClass('packages-button-active')) {
            packagesBtn.removeClass('packages-button-active').css('display', 'none')
            toursBtn.addClass('tours-button-active').css('display', 'inline')
            toursIndex.css('display', 'none')
            packagesIndex.css('display', 'block')

            let url_params = {};
            location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (s, k, v) {
                url_params[k] = v
            })

            let url = $('#index-packages').data('url')

            if (url_params.date) url = url + '/' + url_params.date

            $.get(url, (response) => {
                console.log(url)
                $('#packages-index').html(response.html)
            })
        } else {
            toursBtn.removeClass('tours-button-active').css('display', 'none')
            packagesBtn.addClass('packages-button-active').css('display', 'inline')
            toursIndex.css('display', 'block')
            packagesIndex.css('display', 'none')
        }
    }

});