import Sortable from 'sortablejs'

function initSortable() {
    $('.js-sortable').each(function () {
        let reindex = () => {
            let $that = $(this);
            let data = [];
            $(this).children().each(function () {
                data.push({ id : $(this).data('id'), order : $(this).index() })
            });
            $.post($that.data('url'), { data }, () => window.showNotification('Список успешно отсортирован'));
        };

        Sortable.create($(this)[0], {onEnd : reindex});
    });
}

initSortable();

window.initSortable = initSortable;