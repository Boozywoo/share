$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on('submit', '.js_ajax-form', submitAjaxForm);

window.processAjaxSubmit = ($form, onSuccess, onError) => response => {
    $form.trigger('form-ajax', [response]);
    if (response.result == 'success') {
        $form.trigger('form-ajax-success', [response]);
        if (response.message) toastr.success(response.message);
        if (response.redirect) setTimeout(() => window.location.href = response.redirect, 0);
        if ($form.hasClass('js_form-step-order')) {
            setTimeout(() => window.location.href = '/order', 0)
        };
    } else {
        $form.trigger('form-ajax-error', [response]);
        toastr.error(response.message || 'Ошибка при отправке формы!');
    }
    $form.find('input[type="submit"]').attr('disabled', false);
    $form.find('.js_btn').attr('disabled', false);
}

function submitAjaxForm() {
    let $form = $(this);
    $form.find('input[type="submit"]').attr('disabled', true);
    $form.find('.js_btn').attr('disabled', true);
    $form.ajaxSubmit({
        data: {'is_ajax': 1},
        success: window.processAjaxSubmit($form)
    });
    return false;
}

function init() {
    maskPhone();
}

init();

function maskPhone() {
    // $('.js_mask-phone').inputmask('+375 (99) 999-99-99');
}

function datePicker() {
    $.fn.datepicker.dates['ru'] = {
        days: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"],
        daysShort: ["Вск", "Пнд", "Втр", "Срд", "Чтв", "Птн", "Суб"],
        daysMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
        months: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
        monthsShort: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
        today: "Сегодня",
        clear: "Очистить",
        format: "dd.mm.yyyy",
        weekStart: 1
    };
    $('.js_date-pick').datepicker({
        format: "dd.mm.yyyy",
        todayHighlight: true,
        autoclose: true,
        language: 'ru',
    });
    $('.js_datepicker').datepicker({
        format: "dd.mm.yyyy",
        todayHighlight: true,
        autoclose: true,
        language: 'ru',
    });
}

window.datePicker = datePicker;

datePicker();
