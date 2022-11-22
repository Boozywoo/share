import Sortable from 'sortablejs'

function initSortableStation() {
    $('.js-sortable-station').each(function () {
        let reindex = () => {
            $('.js_reindex-stations').trigger('sortable-stations');
            window.showNotification('Остановки успешно отсортированы')
        };

        Sortable.create($(this)[0], {onEnd : reindex, handle: ".js_multiple-order" });
    });

    $('.js-sortable-routes').each(function () {
        var reindex = function reindex() {
            var form = $('#sort-form');
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                success: function(data)
                {
                    if (data.status == 'success') {
                        window.showNotification(data.message);
                    }
                }
            });
        };

        _sortablejs2.default.create($(this)[0], {onEnd: reindex, handle: ".js_multiple-order"});
    });

}
$(document).ready(function () {
    $(document).on('multiple-added', '.js_reindex-stations', window.reindexBlocks('stations', ['station_id', 'time', 'cost_start', 'cost_finish', 'tickets_from', 'tickets_to'], '', ['']));
    $(document).on('sortable-stations', window.reindexBlocks('stations', ['station_id', 'time', 'cost_start', 'cost_finish', 'tickets_from', 'tickets_to'], '', ['']));
    $(document).on('multiple-removed', window.reindexBlocks('stations', ['station_id', 'time', 'cost_start', 'cost_finish', 'tickets_from', 'tickets_to'], '', ['']));

    initSortableStation();
})


window.initSortableStation = initSortableStation;