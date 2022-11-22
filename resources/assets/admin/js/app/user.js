$(document).ready(function() {
    $(document).on('change', '.js_checkbox-all', checkboxAll);
    $(document).on('change', '.js_checkbox', checkbox);

    function checkboxAll() {
        $(this).closest('.js_checkbox-wrap').find('.js_checkbox').prop('checked', $(this).is(':checked')).change();
    }

    function checkbox() {
        var $table = $(this).closest('.js_checkbox-wrap');
        var $checked = $table.find('.js_checkbox:checked');
        var $all = $table.find('.js_checkbox');
        if($checked.length == $all.length) {
            $table.find('.js_checkbox-all').prop('checked', true);
        } else {
            $table.find('.js_checkbox-all').prop('checked', false);
        }
    }

    jQuery.expr[':'].contains = function (a, i, m) {
        return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
    };
    $(document).on('keyup', '.js_checkbox-search-input', function () {
        var input = $(this),
            value = input.val(),
            checkboxBox = input.closest('.js_checkbox-search').siblings('.js_checkbox-wrap');

        if (value.length > 0) {
            checkboxBox.children().hide();
            checkboxBox.find('label:contains(' + $(this).val() + ')').closest('.checkbox').show().next().filter('.row').show();
        } else {
            checkboxBox.children().show();
        }
    });
    $(document).on('click', '.js_checkbox-search-filter', function () {
        var filter = $(this),
            value = filter.data('filter'),
            checkboxBox = filter.closest('.js_checkbox-search').siblings('.js_checkbox-wrap');
        filter.siblings('.js_checkbox-search-input').val('');

        if (value == 'all') {
            checkboxBox.children().show();
        }

        if (value == 'selected') {
            checkboxBox.children().hide().find('.js_checkbox:checked').parent().show().next().filter('.row').show();
        }
    });
});
