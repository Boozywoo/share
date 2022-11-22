$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('change', '.js_ajax-change', ajaxChange);
    $(document).on('submit', '.js_form-ajax', submitFormAjax);
    $(document).on('click', '.js_panel_confirm', confirm);
    $(document).on('click', '.js_panel_choice', reportChoice);

    function confirm(e) {
        e.preventDefault();
        let $link = $(this);
        let url = $link.attr('href');
        let method = $link.attr('method') ? $link.attr('method') : 'get';
        let question = $(this).data('question') || 'Вы действительно хотите удалить?';
        let textSuccess = $(this).data('success') || 'Удаление прошло успешно';
        var reload = $(this).data('reload');
        bootbox.confirm({
            message: question,
            callback: function (result) {
                if (result) {

                    $.ajax({
                        url: url,
                        method: method,
                        success: (data) => {
                            if (data.result == 'success') {
                                if ($link.hasClass('js_update-filter')) {
                                    $('.js_table-search select:first').trigger('change');
                                } else {
                                    $link.closest('tr').remove();
                                }
                                if ($link.hasClass('js_update-data')) {
                                    updateData(data);
                                }

                                window.showNotification(textSuccess, 'success');
                                if (reload) {
                                    window.location.reload(false);
                                }
                            } else {
                                window.showNotification(data.message || 'Ошибка', 'error');
                            }
                        }
                    });
                }
            }
        })
        return false;
    }

    function submitFormAjax(e) {
        e.preventDefault();
        let $form = $(this);
        $form.find('button').attr('disabled', true)
        $form.trigger('panel-form-ajax-submitted');
        $('.wrapper-spinner').show();
        $form.find('.form-group').removeClass('has-error');
        $form.find('.error-block').html('');

        $form.ajaxSubmit({
            success: function (data) {

                $('.wrapper-spinner').hide();
                $form.find('button').attr('disabled', false)
                if (data.result == 'success') {
                    $form.trigger('panel-form-ajax-success', [data]);
                    if ($form.hasClass('js_form-ajax-redirect')) {
                        let redirectLink = data.link || data.redirect;
                        setTimeout(() => window.location.href = redirectLink, 2000);
                    }
                    if ($form.hasClass('js_form-ajax-popup')) {
                        $form.closest('.modal').modal('hide');
                    }
                    if ($form.hasClass('js_form-update-data')) {
                        updateData(data);
                    }
                    if ($form.hasClass('js_form-ajax-table')) {
                        $('.js_table-search select:first').trigger('change');
                    }
                    if ($form.hasClass('js_form-current-page')) {
                        let $currentPage = $('.js_current-page');
                        if ($currentPage.length) {
                            $currentPage.click();
                        }
                    }
                    if (data.message) {
                        window.showNotification(data.message, 'success');
                    } else {
                        window.showNotification('Данные успешно сохранены', 'success');
                    }
                    if (data.view) {
                        $('.js_table-wrapper').html(data.view);
                    }

                    // window.countPull();
                }
                else if (data.result == 'warning') {
                    if (data.message) {
                        window.showNotification(data.message, 'warning');
                    }
                    if (data.view) $($form.data('wrap')).html(data.view);
                       if (data.view_sub) $($form.data('wrap-sub')).html(data.view_sub);
                }
                else {
                    $form.trigger('panel-form-ajax-error', [data]);
                    $.each(data.errors, function (input, errors) {
                        let inputArray = input.split('.');
                        let $input = $form.find(':input[name="' + input + '"]');
                        if (!$input.length && inputArray.length == 1) {
                            $input = $form.find(':input[name="' + inputArray[0] + '[]"]:eq(' + inputArray[1] + ')');
                        }
                        if (inputArray.length == 2) {
                            $input = $form.find(`:input[name="${inputArray[0]}[${inputArray[1]}]"]`);
                        }
                        if (inputArray.length == 3) {
                            $input = $form.find(`:input[name="${inputArray[0]}[${inputArray[1]}][${inputArray[2]}]"]`);
                        }
                        let text = '';
                        $.each(errors, (i, error) => text += error + "<br>");
                        if ($input.length) {
                            let $wrapper = $input.closest('.form-group');
                            let $error_block = $wrapper.find('.error-block');
                            $wrapper.addClass('has-error');
                            let $help_block = '<span class="help-block">' + text + '</span>';
                            $error_block.append($help_block);
                        } else {
                            window.showNotification(text, 'error');
                        }
                    });
                    if (data.message) {
                        window.showNotification(data.message, 'error');
                    } else {
                        window.showNotification('Ошибка сохранения данных', 'error');
                    }
                    if (data.view) {
                        $('.js_table-wrapper').html(data.view);
                    }
                }
            }
        });
        return false;
    }

    function ajaxChange() {
        let val = $(this).val()
        let url = $(this).data('url')
        let wrapper = $(this).data('wrapper')
        $.get(url, {val: val}, (response) => {
            if (response.html) {
                $(`.${wrapper}`).html(response.html)
            } else {
                $(`.${wrapper}`).html('')
            }
            if (response.message) window.showNotification(response.message, 'error');
        })
    }

    function reportChoice(e) {
        e.preventDefault();
        let $link = $(this);
        let date_type = $link.data('date-type');
        var dialog = bootbox.dialog({
            title: $link.data('title'),
            message: "<p>Выберите, какие брони отображать в отчёте.</p>",
            size: 'large',
            buttons: {
                noclose: {
                    label: "Все активные",
                    className: 'btn-warning',
                    callback: function(){
                        document.getElementById(date_type+"-date-all").click();
                    }
                },
                ok: {
                    label: "Оплаченные онлайн",
                    className: 'btn-info',
                    callback: function(){
                        document.getElementById(date_type+"-date-pay").click();
                    }
                }
            }
        });
        return false;
    }

});
