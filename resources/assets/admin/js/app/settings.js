$(document).ready(function () {
    $(document).on('click', '.closes', deleteImage)

    let type = $("#ticket_type > option:selected").val();

    if (type == 2) {
        $("#field").attr("hidden", false);
    } else {
        $("#field").attr("hidden", true);
    }
    $("#ticket_type").on('change', function () {
        type = $("#ticket_type > option:selected").val();

        if (type == 2) {
            $("#field").attr("hidden", false);
        } else {
            $("#field").attr("hidden", true);
        }
    });
});

function deleteImage() {
    let path = $(this).attr('path')
    $.post("/admin/settings/clients_interface_settings/image-delete", { imagePath: path }).done(function (data) {
        location.reload();
    });
}

$(document).on('change', '#font_size', function () {
    var v = $(this).val();
    $('#example_size').css('font-size', v + 'px');
});

$(document).on('change', '#border_radius', function () {
    var v = $(this).val();
    $('#example_radius').css('border-radius', v + 'px');
});

$(document).on('change', '#opacity', function () {
    var v = $(this).val();
    $('#example_opacity').css('opacity', v);
});

$(document).on('click', '.button_color', function () {
    var v = $(this).val();
    $('#button_color').val(v);
});

$(document).on('click', '.font_color', function () {
    var v = $(this).val();
    $('#font_color').val(v);
});

$(document).on('click', '.background_color', function () {
    var v = $(this).val();
    $('#background_color').val(v);
});

$(document).on('click', '.font_color_authorization_buttons', function () {
    var v = $(this).val();
    $('#font_color_authorization_buttons').val(v);
});