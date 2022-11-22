@if($schedules->count())
<div class="table-responsive">
    <table class="table table-condensed table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ trans('admin_labels.status') }}</th>
            @if ($schedules->first()->flight_number)
                <th>{{ trans('admin_labels.flight_number_title') }}</th>
            @endif
            <th>{{ trans('admin_labels.dates') }}</th>
            <th>{{ trans('admin_labels.week_days') }}</th>
            <th>{{ trans('admin_labels.route_id') }}</th>
            <th>{{ trans('admin_labels.bus_id') }}</th>
            <th>{{ trans('admin_labels.repeat') }}</th>
            <th>{{ trans('admin_labels.price') }}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($schedules as $schedule)
            <tr>
                <td>{{ $schedule->id }}</td>
                <td>{!! trans('pretty.statuses.'. $schedule->status ) !!}
                    @if ($schedule->route->is_transfer && $schedule->route->flight_type == 'arrival')
                        &nbsp;<img src="{{asset('assets/admin/images/landing.png')}}" alt="">
                    @endif
                    @if ($schedule->route->is_transfer && $schedule->route->flight_type == 'departure')
                        &nbsp;<img src="{{asset('assets/admin/images/takeoff.png')}}" alt="">
                    @endif
                </td>
                @if ($schedule->flight_number)
                    <td>
                        {{ $schedule->flight_ac_code }}-{{ $schedule->flight_number}}
                        {{ '('.mb_strtolower(trans('admin_labels.flight_types.'.$schedule->route->flight_type)).': '.$schedule->prettyFlightTime.')' }}
                    </td>
                @endif
                <td>{!! $schedule->prettyDate !!}</td>
                <td>{{ $schedule->weekDays }}</td>
                <td>{{ $schedule->route->name }}</td>
                <td>{{ $schedule->bus->number ?? ''}}</td>
                <td>{{ $schedule->repeat ? trans('admin.home.yes') : trans('admin.home.no') }}</td>
                <td>{{ $schedule->prettyPrice }}</td>
                <td class="td-actions">
                    <a href="{{route ('admin.'. $entity . '.edit', $schedule)}}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="{{ trans('admin.filter.edit') }}">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="{{route ('admin.'. $entity . '.copy', [$schedule, 1]) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="{{ trans('admin.filter.copy') }}">
                        <i class="fa fa-plus"></i>
                    </a>
                    <a href="{{route ('admin.' . $entity . '.delete', $schedule)}}" class="btn btn-sm btn-danger js_panel_confirm" data-toggle="tooltip" title="{{ trans('admin.filter.delete') }}">
                        <i class="fa fa-trash-o "></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@else
    <p class="text-muted">{{ trans('admin.users.nothing') }}</p>
@endif