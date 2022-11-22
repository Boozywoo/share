@extends('panel::layouts.main')

@section('title', $schedule->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection

@section('main')

    {!! Form::model($schedule, ['route' => 'admin.'. $entity . '.store', 'class' => "ibox form-horizontal js_form-ajax js_form-ajax-reset"])  !!}
        {!! Form::hidden('id') !!}
        {!! Form::hidden('is_days_rotate', 0, ['id' => 'is_days_rotate']) !!}
        <div class="ibox-content">
            <h2>{{ $schedule->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}</h2>
            <div class="hr-line-dashed"></div>
            <div class="row">
                <div class="col-md-6">
                    {{ Form::panelSelect('route_id', $routes, null, ['class' => 'form-control js_route-change', 'data-url' => route('admin.routes.info')]) }}
                    {{ Form::panelSelect('bus_id', $buses, null, ['class' => 'form-control js_bus-change', 'data-url' => route('admin.schedules.getDriverId')]) }}
                    {{ Form::panelRadio('repeat', $schedule->id ? $schedule->repeat : true) }}
                    {{ Form::panelRadio('is_collect', $schedule->is_collect ? $schedule->is_collect : false) }}
                    {{ Form::panelRadio('reservation_by_place', $schedule->id ? $schedule->reservation_by_place : false) }}
                    @if($schedule->id)
                        {{ Form::panelSelect('status', trans('admin.companies.statuses')) }}
                    @endif
                </div>
                <div class="col-md-6">
                    {{ Form::panelText('date_start_date', $schedule->date_start ? $schedule->date_start->format('d.m.Y') : Carbon\Carbon::now()->format('d.m.Y'), 'js_datepicker', ['data-date-start-date' => '0d']) }}
                    {{ Form::panelText('date_finish_date', $schedule->date_finish ? $schedule->date_finish->format('d.m.Y') : Carbon\Carbon::now()->addMonths(2)->format('d.m.Y'), 'js_datepicker') }}
                    {{ Form::panelText('date_start_time', $schedule->date_start ? $schedule->date_start->format('H:i') : Carbon\Carbon::now()->format('H:i'), 'time-mask') }}
                    @include('admin.schedules.edit.flight_data')
                </div>
                <div class="col-md-12">
                    <br>
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item active" id="week-tab">
                            <a class="nav-link text-success" data-toggle="tab" href="#" role="tab">По дням недели</a>
                        </li>
                        <li class="nav-item" id="rotation-tab">
                            <a class="nav-link text-success" data-toggle="tab" href="#" role="tab">Через день</a>
                        </li>
                    </ul>

                    <div class="table-responsive">
                        <table class="table table-mini table-condensed" id="week-table">
                            <thead>
                            <tr>
                                <th><strong>{{trans('admin.users.day')}}</strong></th>
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
                                    @if(!$schedule || $scheduleDay)
                                        <tr>
                                            <td>{{ $dayName }}</td>
                                            <td class="text-center">
                                                @if($schedule->id)
                                                    <p>{{trans('admin.home.yes')}}</p>
                                                @else
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
                                                @endif
                                            </td>
                                            <td class="text-center form-group">
                                                {!! Form::select("days[$dayKey][driver_id]", $drivers, $schedule->id && $scheduleDay ? $scheduleDay->driver_id : ($system->id ?? null), 
                                                    ['class' => 'js_driver-select no-display-fields', 'day' => $dayKey]
                                                ) !!}
                                                <span class="error-block"></span>
                                            </td>
                                            <td class="text-center form-group">
                                                @if($schedule->id)
                                                    <p>{{ $scheduleDay->price }}</p>
                                                @else
                                                    {!! Form::text("days[$dayKey][price]", $schedule->id && $scheduleDay ? $scheduleDay->price : 10, 
                                                        ['class' => 'no-display-fields schedule-price', 'day' => $dayKey]) !!}
                                                    <span class="error-block"></span>
                                                @endif
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
                        <table class="table table-mini table-condensed hide" id="rotation-table">
                            <thead>
                            <tr>
                                <th><strong>{{trans('admin.users.day')}}</strong></th>
                                <th class="text-center"><strong>{{trans('admin.schedules.active')}}</strong></th>
                                <th class="text-center"><strong>{{trans('admin.drivers.driver')}}</strong></th>
                                <th class="text-center"><strong>{{trans('admin.schedules.cost')}}</strong></th>
                            </tr>
                            </thead>
                            <tbody>
                                @php($dateStart = Carbon\Carbon::now())
                                @foreach([10, 11] as $dayKey)
                                    <tr>
                                        <td id="rot-day-{{ $loop->iteration }}">
                                            {{ $dateStart->copy()->addDays($dayKey - 10)->format('d.m').', '.$dateStart->copy()->addDays($dayKey-10+2)->format('d.m').', '.$dateStart->copy()->addDays($dayKey-10+4)->format('d.m').' ...' }}
                                        </td>
                                        <td class="text-center">
                                            @if($schedule->id)
                                                <p>{{trans('admin.home.yes')}}</p>
                                            @else
                                                <div class="radio radio-warning radio-inline">
                                                    {{ Form::radio("days[$dayKey][active]", 1, false, ['id' => "days[$dayKey][active]" .'-yes',
                                                        'class' => 'js-days-yes-or-no', 'day' => $dayKey]) }}
                                                    <label for="{{ "days[$dayKey][active]" }}-yes">{{trans('admin.home.yes')}}</label>
                                                </div>
                                                <div class="radio radio-danger radio-inline">
                                                    {{ Form::radio("days[$dayKey][active]", 0, true, ['id' => "days[$dayKey][active]".'-no',
                                                        'class' => 'js-days-yes-or-no', 'day' => $dayKey]) }}
                                                    <label for="{{ "days[$dayKey][active]" }}-no">{{trans('admin.home.no')}}</label>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center form-group">
                                            {!! Form::select("days[$dayKey][driver_id]", $drivers, ($system->id ?? null), ['class' => 'js_driver-select no-display-fields', 
                                                'day' => $dayKey]) !!}
                                            <span class="error-block"></span>
                                        </td>
                                        <td class="text-center form-group">
                                            @if($schedule->id)
                                                <p>{{ $scheduleDay->price }}</p>
                                            @else
                                                {!! Form::text("days[$dayKey][price]", 10,
                                                    ['class' => 'no-display-fields schedule-price', 'day' => $dayKey]) !!}
                                                <span class="error-block"></span>
                                            @endif
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
                    </div>
                </div>
            </div>
        </div>
        <div class="ibox-footer">
            {{ Form::panelButton() }}
        </div>
    {!! Form::close() !!}
@endsection
@push('scripts')
    <script>
        $(document).ready(function()
        {
            $('#week-tab a').on('click', function () {
                $('.nav-tabs li').removeClass('active');
                $('#week-tab').addClass('active');
                $('.table-condensed').removeClass('hide');
                $('#rotation-table').addClass('hide');
                $('#is_days_rotate').val(0);
                $('#rotation-table input[id$="active\\]-no"]').prop('checked', true);
                selectNoOrYesOnShedule();
            });
            $('#rotation-tab a').on('click', function () {
                $('.nav-tabs li').removeClass('active');
                $('#rotation-tab').addClass('active');
                $('.table-condensed').removeClass('hide');
                $('#week-table').addClass('hide');
                $('#is_days_rotate').val(1);
                $('#week-table input[id$="active\\]-no"]').prop('checked', true);
                selectNoOrYesOnShedule();
            });

            $('#date_start_date').on('change', function () {
                var curDate = new Date($(this).datepicker('getDate'));
                curDate.setDate(curDate.getDate() + 1);
                $('#rot-day-2').text(curDate.getDate().toString().padStart(2, '0') + "." + (curDate.getMonth()+1).toString().padStart(2, '0')+', ');
                curDate.setDate(curDate.getDate() + 2);
                $('#rot-day-2').text($('#rot-day-2').text()+curDate.getDate().toString().padStart(2, '0') + "." + (curDate.getMonth()+1).toString().padStart(2, '0')+', ');
                curDate.setDate(curDate.getDate() + 2);
                $('#rot-day-2').text($('#rot-day-2').text()+curDate.getDate().toString().padStart(2, '0') + "." + (curDate.getMonth()+1).toString().padStart(2, '0')+' ...');
                var curDate = new Date($(this).datepicker('getDate'));
                $('#rot-day-1').text(curDate.getDate().toString().padStart(2, '0') + "." + (curDate.getMonth()+1).toString().padStart(2, '0')+', ');
                curDate.setDate(curDate.getDate() + 2);
                $('#rot-day-1').text($('#rot-day-1').text()+curDate.getDate().toString().padStart(2, '0') + "." + (curDate.getMonth()+1).toString().padStart(2, '0')+', ');
                curDate.setDate(curDate.getDate() + 2);
                $('#rot-day-1').text($('#rot-day-1').text()+curDate.getDate().toString().padStart(2, '0') + "." + (curDate.getMonth()+1).toString().padStart(2, '0')+' ...');

            });

            function selectNoOrYesOnShedule() {
                $(".js-days-yes-or-no").each(function() {
                    let day = $(this).attr('day');
                    if($(this).is(':checked')) { 
                        if($(this).val() == 0) {
                            $(".no-display-fields").each(function() {
                                if($(this).attr('day') == day){
                                    $(this).attr('hidden', ''); 
                                } 
                            }); 
                        } 
                    } 
                });
            }
        });

    </script>
@endpush