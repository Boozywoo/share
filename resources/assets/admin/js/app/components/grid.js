$(document).ready(function() {
    window.reindexBlocks = (parentName, parentFields, childName, childFields) => {
        return function () {
            let i = 0;
            $(`.js_reindex-${parentName}`).each(function () {
                let newName = `${parentName}[${i}]`;
                parentFields.map(
                    parentField => {
                        let attribute = `${newName}[${parentField}]`;
                        $(this).find(`.js_${parentName}-${parentField}`).attr('name', attribute);
                    }
                );
                let j = 0;
                $(this).find(`.js_${parentName}-${childName}`).each(function () {
                    childFields.map(
                        childField => {
                            let nameOptionName = `${newName}[${childName}][]`;
                            $(this).find(`.js_${parentName}-${childName}-${childField}`).attr('name', nameOptionName);
                        }
                    )
                    j++;
                });
                i++;
            });
        };
    };

    $(document).on('click', '.js_multiple-add', multipleAdd);
    $(document).on('click', '.js_multiple-remove', multipleRemove);

    function multipleAdd(e) {
        e.preventDefault();
        let name = $(this).data('name');
        let $wrapper = $(this).closest('.js_multiple-wrapper');
        let $clone = $wrapper.find('.js_multiple-row-clone[data-name="' + name + '"]');
        let $row = $clone.clone(true);
        let $inputs = $row.find(':input');
        $inputs.each(function () {
            let $input = $(this);
            $input.prop('disabled', false);
            if ($input.hasClass('js_panel_input-select2')) {
                $input.next().remove();
                $input.removeData('select2').select2();
            }
        });
        $row.insertBefore($clone);
        $row.removeClass('js_multiple-row-clone');
        $row.trigger('multiple-added');
        return false;
    }

    function multipleRemove(e) {
        e.preventDefault();
        let name = $(this).data('name');
        let $row = $(this).closest('.js_multiple-row[data-name="' + name + '"]');
        let $parent = $row.parent();
        $row.remove();
        $parent.trigger('multiple-removed');
        return false;
    }

});
