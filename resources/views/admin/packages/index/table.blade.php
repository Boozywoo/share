<div id="packages-index">
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
            <tr>

                <th>{{ trans('admin_labels.status') }}</th>
                <th>{{ trans('admin_labels.date_start_time') }}</th>
                <th>{{ trans('admin_labels.driver_id') }}</th>
                <th>{{ trans('admin_labels.direction') }}</th>
                <th>{{ trans('admin_labels.name_sender') }}</th>
                <th>{{ trans('admin_labels.phone_sender') }}</th>
                <th>{{ trans('admin_labels.name_receiver') }}</th>
                <th>{{ trans('admin_labels.phone_receiver') }}</th>
                <th>{{ trans('admin_labels.package_from') }}</th>
                <th>{{ trans('admin_labels.package_destination') }}</th>
                <th>{{ trans('admin_labels.price') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($packages as $package)
                <tr>
                    <td>
                        @if($package->status == 'active')
                            <span class="btn-xs btn-info">{{ trans('admin_labels.package_status_active') }}</span>
                        @elseif($package->status == 'awaiting')
                            <span class="btn-xs btn-primary">{{ trans('admin_labels.package_status_awaiting') }}</span>
                        @elseif($package->status == 'returned')
                            <span class="btn-xs btn-warning">{{ trans('admin_labels.package_status_returned') }}</span>
                        @elseif($package->status == 'completed')
                            <span class="btn-xs btn-success">{{ trans('admin_labels.package_status_completed') }}</span>
                        @endif
                    </td>
                    <td>{{ $package->tour->date_time_start }}</td>
                    <td>{{ $package->tour->driver->full_name }} {{ $package->tour->driver->last_name }} {{ $package->tour->driver->middle_name }}</td>
                    <td>{{ $package->tour->route->name }}</td>
                    <td>{{ $package->name_sender }}</td>
                    <td>{{ $package->phone_sender }}</td>
                    <td>{{ $package->name_receiver }}</td>
                    <td>{{ $package->phone_receiver }}</td>
                    <td>
                    @if(!$package->from_station_id)
                        {{ $package->package_from }}
                    @else
                        {{ $package->stationFrom->name }}
                    @endif
                    <td>
                        @if(!$package->destination_station_id)
                            {{ $package->package_destination }}
                        @else
                            {{ $package->stationTo->name }}
                        @endif
                    </td>
                    <td>{{ $package->price }} {{ $package->currencyName->alfa }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>