@if(!empty($themeName) and ($themeName == 'black' or $themeName == 'default'))
    <style>
        h2 {
            color: #ffffff;
        }

        label {
            color: #ffffff;
        }

        table {
            color: #ffffff;
        }
        .table-striped > tbody > tr:nth-of-type(2n+1) {
            background-color: #414141;
        }
        
        h3 {
            color: #ffffff;
        }

        .alert-success {
            background-color: grey;
        }
        
        p {
            color: #ffffff;
        }

        b {
            color: #ffffff;
        }

        span {
            color: #ffffff;
        }

        .phpdebugbar span {
            color: #000;
        }

        .navbar {
            background: rgb(70 71 70);
        }

        .navbar-static-top {
            background: rgba(0,0,0,0.3) !important;
        }

        .nav-header {
            background: none! important
        }

        .nav-header .text-muted {
            color: #fff;
        }

        .dropdown-menu>li>a:hover {
            color: #fff;
        }

        .modal-body {
            position: relative;
        }

        .modal-content .modal-footer .btn-default {
            color: #fff;
        }

        .modal-content .modal-footer .btn-default:active {
            color: #000;
        }

        .modal-content button.close {
            position: absolute;
            right: 0;
            top: 0;
            padding: 0.5rem;
            margin-top: 0 !important;
            color: #fff;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #4c4c4c;
        }
        #map * ymaps, #map * input, #map-all * ymaps, #map-all * input {
            background-color: transparent !important;
            color: black !important;
        }

        input, ymaps {
            background-color: #414141 !important;
            color: #ffffff !important;
        }

        
        /* input:-webkit-autofill {-webkit-box-shadow: 0 0 0 30px #414141 inset; } */

        input:-webkit-autofill, 
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active { 
            -webkit-box-shadow: 0 0 0 30px #414141 inset !important;
            -webkit-text-fill-color: #ffffff !important;
        }

        .input-group-addon {
            background-color: #414141 !important;
        }
        
        select {
            background-color: #414141 !important;
            color: #ffffff !important;
        }

        #side-menu {
            background-color: rgba(77, 77, 77, 0.9);
        }

        ul > li > .pjax-link {
            color: #ffffff;
        }

        div {
            color: #ffffff;
        }

        .breadcrumb.admin-panel_item-transparent-bg {
            color: #ffffff;
        }
        
        .row.wrapper.border-bottom.white-bg.page-heading.small-row.row-width-correct {
            background-color: rgba(77, 77, 77, 0.9);
        }

        .table-hover > tbody > tr:hover {
            background-color: #414141 !important;
        }

        .ibox {
            background: rgba(77, 77, 77, 0.9);
        }

        .btn.btn-default {
            color: #ffffff;
            background-color: #414141;
        }

        .btn-warning {
            background-color: #f8ac59 !important;
        }

        .nav li a {
            color: #698ba9;
        }

        .nav-tabs>li.active>a {
            color: #fff;
        }
        
        a {
            color: #ffffff;
        }

        a:hover {
            color: #ffffff;
            text-decoration: underline !important;
        }

        span > .fa-bars {
            color: #414141;
        }
        a > .fa-trash {
            color: #414141;
        }
        a > .fa-search-plus {
            color: #414141;
        }

        .open > .dropdown-menu {
            background-color: #414141;
        }
        .dropdown-menu > li > a:focus, .dropdown-menu > li > a:hover {
            text-decoration: none;
            background-color: #000000;
        }

        .panel-body {
            background-color: #414141;
        }

        /* popup background 
            ex.(admin/tours?status=active_all and click to "add reys" button) 
        */
            .modal-content {
                background-color: #414141;
            }
        /* end */

        /* custom drop list (admin/settings/add_services/create) */
            .select2-dropdown {
                background-color: #414141;
            }
        /* end custom drop list */

        /* gren btn */
            a[class="label btn-primary"] {
                background-color: #1ab394 !important;
                border-color: #1ab394 !important;
            }
        /* end gren btn */

        /* WYSIWYG Editor - bg styles*/
            .fr-toolbar {
                background-color: #414141 !important;
            }
            .fr-box.fr-basic .fr-wrapper {
                background-color: #414141 !important;
            }
        /* End WYSIWYG Editor */

        /* brecket if table top daterange ex.(/admin/users/statistic) */
            .input-daterange .input-group-addon {
                background-color: #414141 !important;
            }
        /* end */

        /* text area background */
            textarea.form-control {
                background-color: #414141;
            }
        /* end */

        /* link for upload background (admin/settings/interface_settings/edit) */
            .bg-upload-link {
                background-color: #414141;
                border: 1px solid #888888;
            }
            .bg-upload-link:hover {
                background-color: #1ab394;
            }
        /* end */

        /* calendar */
            .datepicker table tr td.day.focused, 
            .datepicker table tr td.day:hover {
                background: #222222!important;
            }
            
            .datepicker table td.range.day {
                background: #222222!important;
            }

            .datepicker.datepicker-dropdown.dropdown-menu.datepicker-orient-left.datepicker-orient-top {
                background-color: #414141 !important;
            }

            .datepicker.datepicker-dropdown.dropdown-menu.datepicker-orient-left.datepicker-orient-bottom {
                background-color: #414141 !important;
            }

            .datepicker table tr td.range, .datepicker table tr td.range.disabled, .datepicker table tr td.range.disabled:hover, .datepicker table tr td.range:hover {
                background: #414141!important;
            }
        /* end */

        /* admin/settings/edit Заголовок бронирование */
            fieldset > legend {
                color: #ffffff;
            }
        /* end */

        /* pagination */
        .pagination > li > a, .pagination > li > span {
            background-color: #414141 !important;
        }
        .pagination > li > a:hover {
            background-color: #222222 !important;
        }
        .pagination > .active > span {
            background-color: #222222 !important;
        }
        /* end pagination */
    </style>
@else
    <style>
        a {
            color: #000000 !important;
        }
        a:hover {
            color: #000000;
            text-decoration: underline !important;
        }

        .blackFont {
            color: #fff !important;
        }

        /* link for upload background (admin/settings/interface_settings/edit) */
        .bg-upload-link {
                background-color: #fffefe;
                border: 1px solid #888888;
            }
            .bg-upload-link:hover {
                background-color: #1ab394;
            }
        /* end */

        /* reset */
        #small-chat > a > i {
            color: #ffffff !important;
        }
        .btn.btn-sm.btn-primary.pjax-link {
            color: #ffffff !important;
        }
        .btn.btn-sm.btn-danger.js_panel_confirm {
            color: #ffffff !important;
        }
        .fa-bars {
            color: #ffffff !important;
        }
        .navbar-static-top, .nav-header {
            background: rgba(255,255,255,0.4) !important;
        }

        .navbar {
            background: rgba(255,255,255,0.3);
        }

        .nav-header {
            background: none! important
        }

        .modal-content button.close {
            position: absolute;
            right: 0;
            top: 0;
            padding: 0.5rem;
            margin-top: 0 !important;
            color: #000;
        }

    </style>
@endif

<style>
    div.phpdebugbar-text {
        color: #000000 !important;
    }
</style>