@extends('panel::layouts.main')

@section('title',  trans('admin.schedules.'.($copyMode ? 'copy' : 'edit')))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection

@section('main')

    {!! Form::model($schedule, ['route' => 'admin.'. $entity . '.store', 'class' => "ibox form-horizontal js_form-ajax js_form-ajax-reset"])  !!}
        @if ($copyMode)
            {!! Form::hidden('copy', 1) !!}
        @else
            {!! Form::hidden('id') !!}
        @endif
        <div class="ibox-content">
            <h2>{{ trans('admin.schedules.'.($copyMode ? 'copy' : 'edit')) }}</h2>
            <div class="hr-line-dashed"></div>
            <div class="row">
                <div class="col-md-6">
                    {{ Form::panelSelect('route_id', $routes, $schedule->route_id, ['class' => 'form-control js_route-change', 'data-url' => route('admin.routes.info')]) }}
                    {{ Form::panelSelect('bus_id', $buses, null, ['class' => 'form-control js_bus-change', 'data-url' => route('admin.schedules.getDriverId')]) }}
                    {{ Form::panelRadio('repeat', $schedule->id ? $schedule->repeat : true) }}
                    {{ Form::panelRadio('is_collect', $schedule->is_collect ? $schedule->is_collect : false) }}
                    {{ Form::panelRadio('reservation_by_place', $schedule->id ? $schedule->reservation_by_place : false) }}
                    @if($schedule->id)
                        {{ Form::panelSelect('status', trans('admin.schedules.statuses')) }}
                    @endif

                </div>
                <div class="col-md-6">
                    @if (!$copyMode)
                        {{ Form::panelText('date_start', $schedule->date_start->format('d.m.Y H:i'), '', ['data-date-start-date' => '0d', 'readonly' => 'readonly', 'name' => 'date_start_read']) }}
                        <div style="display: none">
                            {{ Form::panelText('date_start_date', $schedule->date_start ? $schedule->date_start->format('d.m.Y') : Carbon\Carbon::now()->format('d.m.Y'), 'js_datepicker', ['data-date-start-date' => '0d']) }}
                        </div>
                    @else
                        {{ Form::panelText('date_start_date', Carbon\Carbon::now()->format('d.m.Y'), 'js_datepicker', ['data-date-start-date' => '0d']) }}
                    @endif
                    {{ Form::panelText('date_finish_date',  $schedule->date_finish->format('d.m.Y'), 'js_datepicker', ['data-date-start-date' => '0d']) }}
                    {{ Form::panelText('date_start_time', $schedule->date_start ? $schedule->date_start->format('H:i') : Carbon\Carbon::now()->format('H:i'), 'time-mask') }}
                    @include('admin.schedules.edit.flight_data')
                </div>
                <div class="col-md-12">
                    <br>
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item @if(!$schedule->is_days_rotate)active @endif">
                            <a class="nav-link text-success">По дням недели</a>
                        </li>
                        <li class="nav-item @if($schedule->is_days_rotate)active @endif">
                            <a class="nav-link text-success">Через день</a>
                        </li>
                    </ul>

                    <div class="table-responsive">
                        <table class="table table-mini table-condensed @if($schedule->is_days_rotate)hide @endif">
                            <thead>
                            <tr>
                                <th><strong>{{trans('admin.drivers.date')}}</strong></th>
                                <th class="text-center"><strong>{{trans('admin.schedules.active')}}</strong></th>
                                <th class="text-center"><strong>{{trans('admin.drivers.driver')}}</strong></th>
                                <th class="text-center"><strong>{{trans('admin.schedules.cost')}}</strong></th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach(trans('admin.schedules.days') as $dayKey => $dayName)
                                    @php($scheduleDay = $schedule->id ? $schedule->scheduleDays()->where('day', $dayKey)->first() : false)
                                    @if(!$scheduleDay && !$schedule->id)
                                        @php($scheduleDay = true)
                                    @endif

                                    <tr>
                                        <td>{{ $dayName }}</td>
                                        <td class="text-center">
                                                <div class="radio radio-warning radio-inline">
                                                    {{ Form::radio("days[$dayKey][active]", 1, $scheduleDay ? true : false, ['id' => "days[$dayKey][active]" .'-yes',
                                                        'class' => 'js-days-yes-or-no', 'day' => $dayKey]) }}
                                                    <label for="{{ "days[$dayKey][active]" }}-yes">{{trans('admin.home.yes')}}</label>
                                                </div>
                                                <div class="radio radio-danger radio-inline">
                                                    {{ Form::radio("days[$dayKey][active]", 0, $scheduleDay ? !$scheduleDay : true, ['id' => "days[$dayKey][active]".'-no',
                                                        'class' => 'js-days-yes-or-no', 'day' => $dayKey]) }}
                                                    <label for="{{ "days[$dayKey][active]" }}-no">{{trans('admin.home.no')}}</label>
                                                </div>
                                        </td>
                                        <td class="text-center form-group">
                                            {!! Form::select("days[$dayKey][driver_id]", $drivers, $schedule->id && $scheduleDay ? $scheduleDay->driver_id : ($system->id ?? null),
                                                ['class' => 'js_driver-select no-display-fields', 'day' => $dayKey]
                                            ) !!}
                                            <span class="error-block"></span>
                                        </td>
                                        <td class="text-center form-group">
                                            {!! Form::text("days[$dayKey][price]", $schedule->id && $scheduleDay ? $scheduleDay->price : 0,
                                                ['class' => 'no-display-fields schedule-price', 'day' => $dayKey]) !!}
                                            <span class="error-block"></span>
                                            <span class="error-block"></span>
                                            <span class="no-display-fields" day={{$dayKey}}>
                                                <a class="btn btn-default text-center js-button-copy" day={{$dayKey}}>
                                                    <span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>
                                                </a>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <table class="table table-mini table-condensed @if(!$schedule->is_days_rotate)hide @endif" id="rotation-table">
                            <thead>
                            <tr>
                                <th><strong>{{trans('admin.users.day')}}</strong></th>
                                <th class="text-center"><strong>{{trans('admin.schedules.active')}}</strong></th>
                                <th class="text-center"><strong>{{trans('admin.drivers.driver')}}</strong></th>
                                <th class="text-center"><strong>{{trans('admin.schedules.cost')}}</strong></th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($rotateDays as $dayKey => $scheduleDay)
                                @if($scheduleDay)
                                    <tr>
                                        <td id="rot-day-1">{{ $scheduleDay->date_start->format('d.m').', '.$scheduleDay->date_start->copy()->addDays(2)->format('d.m').', '.$scheduleDay->date_start->copy()->addDays(4)->format('d.m').' ...' }}</td>
                                        <td class="text-center">
                                            <p>{{trans('admin.home.yes')}}</p>
                                            {{ Form::hidden("days[$dayKey][active]", 1) }}
                                        </td>
                                        <td class="text-center form-group">
                                            {!! Form::select("days[$dayKey][driver_id]", $drivers, $scheduleDay->driver_id, ['class' => 'js_driver-select no-display-fields',
                                                'day' => $dayKey]) !!}
                                            <span class="error-block"></span>
                                        </td>
                                        <td class="text-center form-group">
                                            {!! Form::text("days[$dayKey][price]", $scheduleDay->price ?? 10, ['class' => 'no-display-fields schedule-price', 
                                                'day' => $dayKey]) !!}
                                            <span class="error-block"></span>
                                            <span class="no-display-fields" day={{$dayKey}}>
                                                <a class="btn btn-default text-center js-button-copy" day={{$dayKey}}>
                                                    <span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>
                                                </a>    
                                            </span>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <div class="ibox-footer">
            {{ Form::panelButton() }}
        </div>
    {!! Form::close() !!}
@endsection