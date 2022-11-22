@extends('panel::layouts.main')
<style>
    .wrapper-content {
        overflow-x: auto;
    }
</style>
@section('title', $template->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i class="fa fa-chevron-left"></i> {{
    trans('admin.filter.back') }}</a>
@endsection



@section('main')

    {!! Form::model($template, ['route' => 'admin.'. $entity . '.store', 'class' => "ibox form-horizontal js_form-ajax js_form-ajax-reset"])  !!}
        {!! Form::hidden('id') !!}
        <div  class="ibox-content" style="min-width: 650px;">
            <h2>{{ $template->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}</h2>
            <div class="hr-line-dashed"></div>
            <div class="row">
                {{ Form::panelText('name') }}
            </div>
            <!-- верхний фильтр -->
            <div class = 'topFilter'>
                <div class = 'left'>
                    <ul class = 'filterElements'>
                        <li>
                            <div class = 'addRowColumnWrapp rowOp'>
                                <span>{{trans('admin.buses.ranges')}}</span>
                                <span class = 'rowQuantity'>4</span>
                                {!! Form::hidden('ranks', 4, ['class' => 'complementaryInput']) !!}
                                <ul class = 'buttons'>
                                    <li class = 'addRow'></li>
                                    <li class = 'removeRow'></li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <div class = 'addRowColumnWrapp columnOp'>
                                <span>{{trans('admin.buses.columns')}}</span>
                                <span class = 'cellQuantity'>7</span>
                                {!! Form::hidden('columns', 7, ['class' => 'complementaryInput']) !!}
                                <ul class = 'buttons'>
                                    <li class = 'removeColumn'></li>
                                    <li class = 'addColumn'></li>
                                </ul>
                            </div>
                        </li>
                        <br style = 'clear: both'/>
                    </ul>
                </div>
                <br style = 'clear: both'>
            </div>
            <!-- верхний фильтр завершение-->
            <!-- Вот сюда код автобуса-->
            <!-- Автобус 1 -->
            <div class = 'templateOptions'>

                <p class = 'autoNumbering'>{{trans('admin.buses.auto')}}<span class = 'yes active'>{{trans('admin.home.yes')}}</span><span class = 'no'>{{trans('admin.home.no')}}</span><p>
{{--                <p class = 'regidNumbering'>{{trans('admin.buses.hard')}}<span class = 'yes active'>{{trans('admin.home.yes')}}</span><span class = 'no'>{{trans('admin.home.no')}}</span><p>--}}
                <p class = 'reverseLetterNubmering'>{{trans('admin.buses.letter')}}<span class = 'yes'>{{trans('admin.home.yes')}}</span><span class = 'no active'>{{trans('admin.home.no')}}</span><p>
                <p class = 'manualEnterPlaceNum'>{{trans('admin.buses.add')}}<span class = 'yes active'>{{trans('admin.home.yes')}}</span><span class = 'no'>{{trans('admin.home.no')}}</span><p>
            </div>
            <div>
                <div class = 'busLayoutBlock'>
                    <div  class = 'busSettingsPanel'>

                    </div>
                    <div class = 'mainBusBlock'>
                        <!-- bus 4 rows -->
                        <div class = 'busBodeyWrapp'>
                            <div class = 'busBodey'>
                                <div class = 'driver' style = 'display: none'>{{trans('admin.drivers.driver')}}</div>
                                <div class = 'seats'>

                                </div>
                                <div class = 'frontPart'></div>
                                <div class = 'backPart'></div>
                                <div class = 'centralPart'></div>
                                <div class = 'mirror leftMirror'></div>
                                <div class = 'mirror rightMirror'></div>
                            </div>
                        </div>
                        <!-- bus 4 rows end -->
                        <br style = 'clear: both'/>
                    </div>
                </div>
            </div>
            <!-- Автобус 1 завершение -->



        </div>
        <div class="ibox-footer">
            {{ Form::panelButton() }}
        </div>
    {!! Form::close() !!}
@endsection
@push('scripts')
    <script>
        $('.addColumn').on('click', function(e) {
            $('.ibox-content').css('min-width', (($('.cellQuantity').first().text()-6)*50+650)+'px');
        });
        $('.removeColumn').on('click', function(e) {
            $('.ibox-content').css('min-width', (($('.cellQuantity').first().text()-8)*50+650)+'px');
        });
    </script>
@endpush
