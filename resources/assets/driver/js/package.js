$(document).ready(function () {

    $(document).on('click', '.set-status-package', setStatusPackage);
    $(document).on('click', '.status-package-awaiting', setAwaiting);
    $(document).on('click', '.status-package-returned', setReturned);
    $(document).on('click', '.status-package-completed', setCompleted);

    function setAwaiting(event) {
        let $button = $(event.target)
        $button.attr('style', 'display: none')
        $(event.target).closest('tr').find('.status-package-returned').attr('style', 'display: inline')
        $(event.target).closest('tr').find('.status-package-completed').attr('style', 'display: inline')
    }


    function setReturned(event) {
        let $button = $(event.target)
        if ($button.hasClass('btn-warning')) {
            $button.removeClass('btn-warning')
            $button.addClass('btn-primary')
            $(event.target).closest('tr').find('.status-package-completed').attr('style', 'display: inline')
            //return
        } else {
            $(event.target).closest('tr').find('.status-package-completed').attr('style', 'display: none')
            $button.removeClass('btn-primary')
            $button.addClass('btn-warning')
        }
    }

    function setCompleted(event) {
        let $button = $(event.target)
        if ($button.hasClass('btn-success')) {
            $button.removeClass('btn-success')
            $button.addClass('btn-primary')
            $(event.target).closest('tr').find('.status-package-returned').attr('style', 'display: inline')
            //return
        } else {
            $(event.target).closest('tr').find('.status-package-returned').attr('style', 'display: none')
            $button.removeClass('btn-primary')
            $button.addClass('btn-success')
        }
    }


    function setStatusPackage(event) {
        let $button = $(event.target)
        let url = $button.data('url')
        $.post(url, (response) => {
        })
    }

});