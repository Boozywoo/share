$(document).ready(function () {

    $(document).on('change', '.js_tariff_change_type', TariffChangeType);
    $(document).on('change', '#id_tariff_type_select', TariffTypeChange);

    function TariffChangeType() {
        let sendData = {
            bus_type_id: $('#id_tariff_bus_type_select').val(),
            type: $('#id_tariff_type_select').val(),
            agreement_id: $("[name='agreement_id']").val()
        };

        $.get('/admin/tariffs/get_min_value', sendData, (response) => {
            $("#min").val(response);
            $("#max").val(parseInt(response) + 1);
        });
    }

    function TariffTypeChange() {
        type = $('[name=type]').val();
        if (type == 'route') {
            $('.tariff_route_group').removeClass('hidden')
                $.get('/admin/tariffs/get_routes', (response) => {
                $('#route_id').empty()
                //$('#route_id').append('<option value="">' + 'Выберите направление' + '</option>');
                $.each(response.routes, function (key, value) {
                    $('#route_id').append('<option value="' + key + '">' + value + '</option>');
                });
                //
                $('#revert_route_id').empty()
                //$('#revert_route_id').append('<option value="">' + 'Выберите направление' + '</option>');
                $.each(response.routes, function (key, value) {
                    $('#revert_route_id').append('<option value="' + key + '">' + value + '</option>');
                });
            });
        } else {
            $('.tariff_route_group').addClass('hidden')
            $('[name=route_id]').val('2')
            $('[name=revert_route_id]').val('2')
        }   //alert($('#route_id').val(), '', $('#revert_route_id').val())
    }

});
