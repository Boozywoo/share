$(document).ready(function () {
    $(document).on('click', '#js_provider-add-new', addProvider);
    $(document).on('click', '.js-btn-remove', removeProvider);

    let fields = ['provider_name_new',
        'provider_number_prefix_new',
        'sms_send',
        'sms_sender',
        'sms_api_login',
        'sms_api_password', 
        'is_latin'
    ];


    function addProvider() {

        let el = $(this).closest('.tab-pane');
        if (validate(el)) {

            var newElement = $(el).closest('.tab-pane').clone();
            // generate new ID
            var id = Math.random().toString(36).substring(7);

            $(newElement).attr('id', 'provider-tab' + id).removeClass('active in');
            $(newElement).find('#js_provider-add-new').removeClass('btn-primary').addClass('btn-danger js-btn-remove').attr('data-id', id).attr('id', '').html('<i class="fa fa-dot-circle-o"></i> ' + 'Удалить оператора');

            $(newElement).find('#provider_name_new').attr('id', 'provider_name' + id).attr('name', 'provider_name[' + id + ']');
            $(newElement).find('#provider_number_prefix_new').attr('id', 'provider_number_prefix' + id).attr('name', 'provider_number_prefix[' + id + ']');
            $(newElement).find('#sms_send').attr('id', 'sms_send' + id).attr('name', 'provider_sms_send[' + id + ']');
            $(newElement).find('#sms_sender').attr('id', 'sms_sender' + id).attr('name', 'provider_sms_sender[' + id + ']');
            $(newElement).find('#sms_api_login').attr('id', 'sms_api_login' + id).attr('name', 'provider_sms_api_login[' + id + ']');
            $(newElement).find('#sms_api_password').attr('id', 'sms_api_password' + id).attr('name', 'provider_sms_api_password[' + id + ']');
            $(newElement).find('#is_latin').attr('id', 'is_latin' + id).attr('name', 'provider_is_latin[' + id + ']');
            $(newElement).find('#provider_active_new').attr('id', 'provider_active' + id).attr('name', 'provider_active[' + id + ']');
            $(newElement).find('#provider_default_new').attr('id', 'provider_default' + id).attr('name', 'provider_default').attr('value', id).addClass('js-default');

            var nav = '<li class="nav-item "><a class="nav-link" id="provider' + id + '" data-toggle="tab" href="#provider-tab' + id + '" role="tab" aria-controls="provider' + id + '" aria-selected="true">'+$('#provider_name_new').val()+'</a></li>';

            // reset form
            $("#js_provider-new input").val('');

            $("#js_provider-new").before(newElement);
            $("#add-tab").before(nav);
            $("#provider" + id).trigger('click');
        }
    }

    function validate(el) {
        var error = false;

        for (var i = 0; i < fields.length; i++) {
            var _field = $(el).find("#" + fields[i]).closest('.form-group');

            if (!document.getElementById(fields[i]).value) {
                _field.addClass('has-error');
                error = true;
            } else {
                _field.removeClass('has-error');
            }
        }

        return !error;
    }

    function removeProvider() {
        let $this = this;

        var providerId = ($($this).attr("data-id"));

        if (confirm('Вы уверены? Нажмите кнопку Сохранить для сохранения изменений.')) {

            if ($(".nav-item").length > 2) {
                $('#provider' + providerId).closest('.nav-item').remove();
                $('#provider-tab' + providerId).remove();

                if (!$(".js-default").checked) {
                    $(".js-default").first().prop('checked', 'checked');
                }

                $('#js_sms-providers a').first().trigger('click');
            } else {
                alert('Это единственный оператор, удалить невозможно :(');
            }


        }
    }


});