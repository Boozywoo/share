$(document).on('click', '.reservatButon', changeDate)

function changeDate() {
    let val = $(this).data('val');
    $('.reservatButon').removeClass('active');
    $(this).addClass('active');
    $('.js_date-pick').val(val);
    $('.js_date-pick').datepicker('setDate', val);

}
