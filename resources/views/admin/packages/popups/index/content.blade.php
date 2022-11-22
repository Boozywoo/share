<div class="ibox-content">
    <button type="button" class="close" data-dismiss="modal">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    <h2>{{  trans('admin_labels.packages') }}</h2>
    <h3 class="text-right">{{ trans('admin_labels.date_start_time') }}:
        {{ $dateStartTime }}
        <br>
        {{ $routeName }}
    </h3>
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
            <tr>
                <th>{{ trans('admin_labels.status') }}</th>
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
    <div class="hr-line-dashed"></div>
    <span class="btn btn-sm btn-danger ml-2" data-dismiss="modal" type="button">Закрыть</span>
</div>