$(document).on('form-ajax-success', '.js_settings-form', submitSettingSuccess)
$(document).on('click', '.js_settings-edit', editSetting)
$(document).on('click', '.js_settings-save', saveSetting)
$(document).on('click', '.js_tickets-cancel', ticketCancel)

function ticketCancel() {
    let orderId = $('.js_tickets-popup .techHiddenInput').val()
    $.post('/profile/tickets', {id: orderId}, (response) => {
        if (response.result == 'success') {
            toastr.success(response.message)
            $(`.js_tickets-tr[data-id=${orderId}]`).remove()
        } else {
            toastr.error(response.message)
        }
        $('.js_tickets-close_popup').click()
    })
}

function saveSetting() {
    $('.js_settings-form').submit()
    return false;
}

function editSetting() {
    $('.js_settings-edit').hide()
    $('.js_settings-save').show()
    $('.js_settings-form').find('.js_settings-input').attr('disabled', false)
    return false;
}

function submitSettingSuccess() {
    $('.js_settings-edit').show()
    $('.js_settings-save').hide()
    $('.js_settings-form').find('.js_settings-input').attr('disabled', true)
}