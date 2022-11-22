$(document).ready(function() {
    // ��� ��� ��� ����, ����� ���� "�����" � "������ ���������
    // ������������" ������������ �� ajax, ������ ���� ������������
    // �������� ������ �������� �� ����������� ������. ��� ����� ����
    // ��� �������� �� �������� �������������� ������������, ���������
    // ������ ������� �������������� ��� ������������ ��������, ����
    // "��������" ����� ���������� �� �������� �����������. ��-�� ����
    // �� ��� ��� ��������� ������������� ������� change, ����������
    // �������� ����� ������� ������� templateChange(), �������
    // ���������� ���� "�����" � "������ ��������� ������������". �
    // ���������� �� ��������, ����������� �� ��, ����� ��������
    {
        $(document).on('click', '.js_template-change', templateClick);

        function templateClick() {
            window.js_template_change_clicked = true;
        }
    }

    // ���������� ������ ������ �������� �� ����������� ������
    $(document).on('change', '.js_template-change', templateChange);
    $(document).on('click', '.js_filter_table-reset', resetSubmitTable);

    function templateChange() {
        if (window.js_template_change_clicked !== true) {
            return;
        }
        window.js_template_change_clicked = false;

        var url = $(this).data('url');
        var val = $(this).val();
        var wrapper = $(this).data('wrapper');
        $.get(url, { val: val }, function (response) {
            if (response.val) {
                $('.' + wrapper).val(response.val);
                $('.js_template').html(response.view);
            }
        });

        // ������ ��������� ���������� ��� �����
        var url1 = $(this).data('url1');
        $.get(url1, { company: val }, function (response) {
            if (response.val) {
                $('.js_template_positions').html(response.view);
            }
        });

        // ������ ��������� ����������� �����
        var url2 = $(this).data('url2');
        var user_id = $('form input[name="id"]').val();
        $.get(url2, {user: user_id, company: val}, function (response) {
            if (response.val) {
                $('.js_template_superiors').html(response.view);
            }
        });
    }


    // Select Company in Buses


    $(document).on('click', '.js_company-select', selectCompanyClick);

    function selectCompanyClick() {
        window.js_company_template_change_clicked = true;
    }

    $(document).on('change', '.js_company-select', selectCompanyChange);

    function selectCompanyChange() {
        if (window.js_company_template_change_clicked !== true) {
            return;
        }
        window.js_company_template_change_clicked = false;

        var url = $(this).data('url');
        var val = $(this).val();
        $.get(url, {val: val}, function (response) {
            if (response.val) {
                $('.js_department_select').html(response.view);
            }
        });
    }
    $(document).on('change', '.review_act .onoffswitch', onoffswitchClick);

    // ���������� ������� �� ��������� � ��������������� �����
    function onoffswitchClick() {
        // ��� ��������� ������� �������� ������������ ����
        // �������� ��������, ��� ���������� - ��������
        if ($(this).find('input:checked').val()) {
            $(this).parent().parent().next().removeClass('invisible');
        } else {
            $(this).parent().parent().next().addClass('invisible');
        }
    }

    // ���������� ����������� ������ � ��������� ��������������� �����
    $(document).on('change', '.js_diagnostic_card_template-change', diagnosticCardTemplateChange);

    function diagnosticCardTemplateChange() {
        var url = $(this).data('url');
        var val = $(this).val();
        //var wrapper = $(this).data('wrapper');
        $.get(url, { val: val }, function (response) {
            if (response.val) {
                //$('.' + wrapper).val(response.val);
                $('.js_template_buttons').html(response.view_buttons);
                $('.js_template').html(response.view);
                $('.diagnostic_card .act_panel').addClass('pace-active');
                if (val.length) {
                    $('.diagnostic_card .buttons').removeClass('pace-active');
                    $('.diagnostic_card .js_template_buttons button').removeClass('btn-success');
                    $('.diagnostic_card .js_template_buttons button').addClass('btn-default');
                    $('.diagnostic_card button0').removeClass('btn-default');
                    $('.diagnostic_card button0').addClass('btn-success');
                    $('.diagnostic_card .act_panel_0').removeClass('pace-active');
                } else {
                    $('.diagnostic_card .buttons').addClass('pace-active');
                }

            }
        });
    }

    // ���������� ������� ������ ������ ���� �������
    $(document).on('click', '.diagnostic_card .buttons button[type="button"]', diagnosticCardButtonClicked);

    function diagnosticCardButtonClicked() {
        // ����� ������� ������
        var btnNum = $(this).data("review_act_template_id");
        // ������� ��������� �� ���� ������
        $('.diagnostic_card .buttons button[type="button"]').removeClass('btn-success');
        $('.diagnostic_card .buttons button[type="button"]').addClass('btn-default');
        // �������� ������, �� ������� ��������
        $(this).removeClass('btn-default');
        $(this).addClass('btn-success');

        // ��� ������ � ������ �������
        var $panels = $('.diagnostic_card .act_panel');
        for (var i = 0; i < $panels.length; i++) {
            if ($panels.eq(i).data("review_act_template_id") == btnNum) {
                // ���������� ������ � ����� �������, ��������������� ������� ������
                $panels.eq(i).removeClass('pace-active');
            } else {
                // �������� ��� ��������� ������
                $panels.eq(i).addClass('pace-active');
            }
        }
    }


    function resetSubmitTable(e) {
        e.preventDefault();
        console.log('1');
        let $form = $('#filter-table');
        $form.find('.select2-block').val(null).trigger('change');
        $(".btn-filter-submit").trigger('click');
        location.reload();
        return false;
    }



});

