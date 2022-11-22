$(document).on('change', '.js_import input', importInput);
$(document).on('click', '.js_import a', clickImportInput);

function importInput() {
    $('.wrapper-spinner').show();
    let url = $(this).data('url');
    let formData = new FormData();
    let file = $(this)[0].files[0];
    $(this).val('');
    formData.append('file', file);
    $.ajax({
        type: 'POST',
        url: url,
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (data) {
            $('.wrapper-spinner').hide();
            if(data.message) window.showNotification(data.message, data.result);
            if(data.view_success) $('[data-ajax=content-success]').html(data.view_success);
        },
        error: function () {
            $('.wrapper-spinner').hide();
        }
    });
}

function clickImportInput() {
    $('.js_import input').click();
    return false;
}

$(document).on('click', '.js_egis_status a', clickEgisStatus);

function clickEgisStatus(e) {
	e.preventDefault();
    $('.wrapper-spinner').show();
    var url = $(this).data('url');
    $.ajax({
        type: 'POST',
        url: url,
        dataType: 'json',
        success: function success(data) {
            $('.wrapper-spinner').hide();
            if (data.message) window.showNotification(data.message, data.result);
        },
        error: function error() {
            $('.wrapper-spinner').hide();
        }
    });
}


$(document).on('click', '.js_egis_send a', clickEgisSend);

function clickEgisSend(e) {
	e.preventDefault();
	$('.wrapper-spinner').show();
	var url = $(this).data('url');
	$.ajax({
		type: 'POST',
		url: url,
		dataType: 'json',
		success: function success(data) {
			$('.wrapper-spinner').hide();
			if (data.message) window.showNotification(data.message, data.result);
		},
		error: function error() {
			$('.wrapper-spinner').hide();
		}
	});
}