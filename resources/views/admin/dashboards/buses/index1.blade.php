@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.list'))

@section('actions')

@endsection

@section('main')
    <div class="ibox">
        <div class="ibox-content">
            <div class="row">
                <div class="col-sm-9"><h2>{{ trans('admin.'. $entity . '.title') }}</h2></div>
                <div class="col-sm-3" style="text-align: right;">
                    <div style=" font-size: 25px; cursor: pointer; display: inline-block;"
                         {{--                     data-url="{{route ('admin.' . $entity . '.filter', [json_encode(request()->all())])}}" --}}
                         data-toggle="modal"
                         onclick="chooseField('all')"
                         data-target="#db_bus-filter">
                        <i class="fa fa-filter"></i>
                    </div>

                    <div class="onoffswitch" style="vertical-align: text-bottom; margin: 2px 6px;">

                        {!! Form::checkbox('hide_filter', 1, request()->has('hide_filter') ? request()->get('hide_filter') : '', ['class' => 'card-checkbox',
                            'style' => 'display: none', 'id' => 'hide_filter'])  !!}
                        {!! Form::labelHtml('hide_filter', '<span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>',
                            ['class' => 'onoffswitch-label']
                        ) !!}

                    </div>

                </div>
            </div>
            <div class="hr-line-dashed"></div>
            {{--            @include('admin.'. $entity . '.index.filter')--}}
            {{--            <div class="hr-line-dashed"></div>--}}
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.index.table')
            </div>
        </div>
        <div class="ibox-footer js_table-pagination">
            @include('admin.partials.pagination', ['paginator' => $buses])
        </div>
    </div>
    <div class="filter-data">
        @include('admin.'.$entity.'.index.filter')
    </div>

    <script>

        var fields_count = "{{count(array_unique($fields['all']))}}";
        var $fields = $(".filter-data .filter-switch .fields-check");
        $(document).ready(function () {

            $(document).on("change", "#field_select_all", checkAll);
            $(document).on("change", ".fields-check", checkOne);

            checkOne();
        });

        function chooseField(field) {
            if (field == 'all' || !field) {
                $(".filter-fields").show();
            } else {
                $(".filter-fields").hide();
                $("#filter-field-" + field).show();
            }
        }

        function checkAll() {
            if ($("#field_select_all").is(':checked')) {
                $fields.prop('checked', true);
            } else {
                $fields.prop('checked', false);
            }
        }

        function checkOne() {
            if ($(".filter-data .filter-switch .fields-check:checked").length == fields_count) {
                $("#field_select_all").prop('checked', true);
            } else {
                $("#field_select_all").prop('checked', false);
            }
        }


    </script>
<!--
    <style>
        .select2-dropdown {
            z-index: 3000 !important;
        }

        .modal-content-body {
            background-color: #414141;
            /*border: 1px solid transparent;*/
            /*border-radius: 4px;*/
            /*box-shadow: 0 1px 3px rgb(0 0 0 / 30%);*/
            /*outline: 0 none;*/
            /*position: relative;*/
            /*background-clip: padding-box;*/
        }

        .select2-results__option:before {
            content: "";
            display: inline-block;
            position: relative;
            height: 20px;
            width: 20px;
            border: 2px solid #e9e9e9;
            border-radius: 4px;
            /*background-color: #fff;*/
            margin-right: 20px;
            color: #fff;
            vertical-align: middle;
        }

        .select2-results__option[aria-selected=true]:before {
            font-family: fontAwesome;
            content: "\f00c";
            color: #fff;
            /*background-color: #f77750;*/
            border: 0;
            display: inline-block;
            padding-left: 3px;
        }

        .select2-container&#45;&#45;default .select2-results__option[aria-selected=true] {
            /*background-color: #fff;*/
        }

        .select2-container&#45;&#45;default .select2-results__option&#45;&#45;highlighted[aria-selected] {
            /*background-color: #eaeaeb;*/
            /*color: #272727;*/
        }

        .select2-container&#45;&#45;default .select2-selection&#45;&#45;multiple {
            margin-bottom: 10px;
            height: auto;
            border-width: 2px;
        }

        .select2-container&#45;&#45;default.select2-container&#45;&#45;open.select2-container&#45;&#45;below .select2-selection&#45;&#45;multiple {
            border-radius: 4px;
        }

        .select2-container&#45;&#45;default.select2-container&#45;&#45;focus .select2-selection&#45;&#45;multiple {
            /*border-color: #f77750;*/
            border-width: 2px;
        }

        .select2-container&#45;&#45;open .select2-dropdown&#45;&#45;below {

            border-radius: 6px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);

        }

        .select2-selection__choice {
            /*display: none !important;*/
        }

        /*.select2-selection__choice__remove { display: none !important; }*/
        .select2-selection&#45;&#45;multiple .select2-selection__choice {
            padding-left: 23px !important;
        }

        .select2-search.select2-search&#45;&#45;inline {
            width: 100% !important;
        }

        .select2-search.select2-search&#45;&#45;inline .select2-search__field {
            width: 100% !important;
            text-align: center !important;
        }

        .form-control {
            border-radius: 10px !important;
        }

        .select2-selection__rendered li {
            background-color: unset !important;
        }

        .select2-container&#45;&#45;default .select2-selection&#45;&#45;multiple .select2-selection__clear {
            margin-top: 0;
        }

        .select2-container&#45;&#45;default.select2-container&#45;&#45;focus .select2-selection&#45;&#45;multiple {
            height: auto !important;
        }
    </style>
@endsection-->
