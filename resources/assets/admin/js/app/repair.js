
$(document).ready(function (){

    $(document).on('change', '.js_department_select', selectDepartmentChange);
    $(document).on('change', '.js_car_select', selectCarChange);
    $(document).on('click', '.js_finish_repair', finishRepair);

    function selectDepartmentChange() {

        var url = $(this).data('url');
        var val = $(this).val();
        $.get(url, {val: val}, function (response) {
            if (response.val) {
                $('.js_cars_template').html(response.view);
            }
        });
    }
    function selectCarChange() {

        var url = $(this).data('url');
        var val = $(this).val();
        $.get(url, {val: val}, function (response) {
            if (response.val) {
                $('.js_cards_template').html(response.view);
            }
        });
    }

    function finishRepair() {
        let href = $(this).data('href');
        let redirect = $(this).data('redirect');
        let status = $(this).data('status');
        let question = $(this).data('question');
        let bus_status = $(this).data('busStatus');
        bootbox.confirm({
            message: question,
            callback: function (result) {
                if (result) {

                    $.ajax({
                        url: href,
                        method: 'post',
                        data: {
                            status: status,
                            bus_status: bus_status
                        },
                        success: function (data) {
                            if (data.result == 'success') {
                                window.showNotification(data.message, 'success');
                                if(typeof changed_parts !== 'undefined'){
                                    changed_parts = [];
                                }
                                location.href = redirect;
                            } else {
                                window.showNotification(data.message, 'error');
                            }
                        }
                    });
                }
            }
        });

    }

    // очистить все поля формы по id

    $(document).on('click', '#clearCarRepairFilter', clearForm);

    function clearForm() {
        $('#createRepairFilter').trigger("reset");
    }

    // $(document).on('change', '.card-checkbox', onoffswitchClick)

    function onoffswitchClick() {
        // ��� ��������� ������� �������� ������������ ����
        // �������� ��������, ��� ���������� - ��������
        if ($(this).find('input:checked').val()) {
            $(this).parent().next().removeClass('invisible');
        } else {
            $(this).parent().next().addClass('invisible');
        }
    }

});

