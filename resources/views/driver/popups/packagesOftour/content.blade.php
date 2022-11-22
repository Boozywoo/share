<div class="ibox-content">
    <button type="button" class="close" data-dismiss="modal">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    <h2>{{  trans('admin_labels.packages') }}</h2>
    <div class="table-responsive">
        <table class="table table-condensed">
            <thead>
            <tr>
                <th></th>
                <th>{{ trans('admin_labels.name_sender') }}</th>
                <th>{{ trans('admin_labels.phone_sender') }}</th>
                <th>{{ trans('admin_labels.name_receiver') }}</th>
                <th>{{ trans('admin_labels.phone_receiver') }}</th>
                <th>{{ trans('admin_labels.package_from') }}</th>
                <th>{{ trans('admin_labels.package_destination') }}</th>
                <th>{{ trans('admin_labels.price') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($packages as $package)
                <tr>
                    <td>
                        <button type="button"
                                class="btn status-package-awaiting btn-sm set-status-package btn-primary"
                                @if($package->status == 'awaiting' || $package->status == 'returned' || $package->status == 'completed')
                                    style="display: none"
                                @endif
                                data-url="{{route('driver.setStatusPackage', [$package->id, 'awaiting'])}}">
                            {{ trans('admin_labels.package_status_awaiting') }}
                        </button>
                        <button type="button"
                                @if($package->status == 'active' || $package->status == 'completed')
                                    style="display: none"
                                @elseif($package->status == 'returned')

                                @endif
                                class="btn status-package-returned btn-sm set-status-package @if($package->status == 'returned') btn-warning @else btn-primary @endif"
                                data-url="{{route('driver.setStatusPackage', [$package->id, 'returned'])}}">
                            {{ trans('admin_labels.package_status_returned') }}
                        </button>
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
                    <td>
                        <button type="button"
                                @if($package->status == 'active' || $package->status == 'returned')
                                    style="display: none"
                                @elseif($package->status == 'completed')

                                @endif
                                class="btn status-package-completed btn-sm set-status-package  @if($package->status == 'completed') btn-success @else btn-primary @endif"
                                data-url="{{route('driver.setStatusPackage', [$package->id, 'completed'])}}">
                            {{ trans('admin_labels.package_status_completed') }}
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="hr-line-dashed"></div>
    <span class="btn btn-sm btn-danger ml-2" data-dismiss="modal" type="button">Закрыть</span>
</div>