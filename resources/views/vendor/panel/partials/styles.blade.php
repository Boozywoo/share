<link href="{{ asset('assets/index/css/bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('assets/panel/css/panel.css') }}" rel="stylesheet">
<link href="{{ elixir('assets/admin/css/styles.css') }}" rel="stylesheet">
<link href="{{ elixir('assets/admin/css/datetimepicker.css') }}" rel="stylesheet">
@include('panel::partials.thems-styles')

<style>
    .wrapper-spinner {
        z-index: 9999;
    }

    .label {
        color:white;
    }

    /* popups for confitm delet item */

    .modal-content .modal-body {
        background-color: #{{ !empty($confirmPopupBgColor) ? $confirmPopupBgColor : 'fff' }};
        color: #{{ !empty($confirmPopupFontColor) ? $confirmPopupFontColor : '000'  }};
    }

    .modal-content .modal-footer {
        background-color: #{{ !empty($confirmPopupBgColor) ? $confirmPopupBgColor : 'fff' }};
        color: #{{ !empty($confirmPopupFontColor) ? $confirmPopupFontColor : '000'  }};
    }


    /*-- select lists --*/

    .selection > .select2-selection {
        background-color: #{{ !empty($confirmPopupBgColor) ? $confirmPopupBgColor : 'fff' }};
        min-height: 40px;
    }

    .select2-selection__rendered li {
        background-color: #{{ !empty($confirmPopupBgColor) ? $confirmPopupBgColor : 'fff' }} !important;
    }

    .select2-selection__choice > span {
        background-color: #{{ !empty($confirmPopupBgColor) ? $confirmPopupBgColor : 'fff' }};
    }
    
    .test_img.test_img {
        background-image: url("{!! !empty($userCustomBgUrl) ? $userCustomBgUrl : false !!}");
    }
</style>