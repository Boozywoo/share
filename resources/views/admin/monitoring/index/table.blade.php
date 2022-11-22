@if($schedules->count())
<div class="table-responsive">
    <table class="table table-condensed table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ trans('admin_labels.status') }}</th>
            <th>{{ trans('admin_labels.dates') }}</th>
            <th>{{ trans('admin_labels.route_id') }}</th>
            <th>{{ trans('admin_labels.bus_id') }}</th>
            <th>{{ trans('admin_labels.repeat') }}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($schedules as $schedule)
            <tr>
                <td>{{ $schedule->id }}</td>
                <td>{!! trans('pretty.statuses.'. $schedule->status ) !!}</td>
                <td>{!! $schedule->StartTime !!}</td>
                <td>{{ $schedule->route->name }}</td>
                <td>{{ $schedule->bus->number }}</td>
                <td>{{ $schedule->repeat ? trans('admin.home.yes') : trans('admin.home.no') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@else
    <p class="text-muted">{{ trans('admin.users.nothing') }}</p>
@endif