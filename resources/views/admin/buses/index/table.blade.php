@if($buses->count())
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('admin_labels.status') }}</th>
                <th>{{ trans('admin_labels.name') }}</th>
                <th>{{ trans('admin_labels.number') }}</th>
                <th>{{ trans('admin_labels.garage_number') }}</th>
                <th>{{ trans('admin_labels.location_status') }}</th>
                <th>{{ trans('admin_labels.company') }}</th>
                <th>{{ trans('admin_labels.vin') }}</th>
                <th>{{ trans('admin_labels.odometer') }}</th>
                <th>{{ trans('admin_labels.fuel') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($buses as $bus)
                <tr>
                    <td>
                        {{ $bus->id }}
                    </td>
                    <td>
                        {!! trans('pretty.statuses.'. $bus->status) !!}
                    </td>
                    <td>
                        <a href="{{route ('admin.'. $entity . '.edit', $bus)}}" class="pjax-link">
                            {{ $bus->name }}
                        </a>
                    </td>
                    <td>
                        {{ $bus->number }}
                    </td>
                    <td>
                        {{ $bus->garage_number }}
                    </td>
                    <td>
                        {{ __('admin.buses.location_statuses.'.$bus->location_status) }}
                    </td>
                    <td>
                        {{ $bus->company->name ?? '' }}
                    </td>
                    <td>
                        {{ $bus->vin }}
                    </td>
                    <td>
                        {{$bus->odometer}}
                    </td>
                    <td>
                        {{$bus->fuel}}
                    </td>
                    <td class="td-actions">
                        {{--
                        <a href="{{route ('admin.'. $entity . '.review_acts.index', ['bus_id' => $bus])}}"
                           class="btn btn-sm btn-warning pjax-link" data-toggle="tooltip"
                           title="{{ trans('admin.buses.review_act') }}">
                            <i class="fa fa-file-text-o"></i>
                        </a>
                        --}}
                        <a href="{{route ('admin.'. $entity . '.diagnostic_cards.index', [$bus])}}"
                           class="btn btn-sm btn-warning pjax-link" data-toggle="tooltip"
                           title="{{ trans('admin.buses.diagnostic_card') }}">
                            <i class="fa fa-list-alt"></i>
                        </a>
                        <a href="{{route ('admin.repair_orders.index', ['bus_id' => $bus])}}"
                           class="btn btn-sm btn-warning pjax-link" data-toggle="tooltip"
                           title="{{ trans('admin.buses.repair') }}">
                            <i class="fa fa-wrench"></i>
                        </a>
                        @permission('view.rents')
                        <a href="{{route ('admin.'. $entity . '.rent.index', ['bus_id' => $bus])}}"
                           class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip"
                           title="{{ trans('admin.buses.rent.title') }}">
                            <i class="fa fa-clock-o"></i>
                        </a>
                        @endpermission
                        <a href="{{route ('admin.'. $entity . '.edit', $bus)}}" class="btn btn-sm btn-primary pjax-link"
                           data-toggle="tooltip" title="{{ trans('admin.filter.edit') }}">
                            <i class="fa fa-edit"></i>
                        </a>
                        @if(Auth::user()->roles->first()->slug == 'admin' or Auth::user()->roles->first()->slug == 'superadmin')
                            <a href="{{route ('admin.' . $entity . '.delete', $bus)}}"
                                class="btn btn-sm btn-danger js_panel_confirm {{ $textLink }}" data-toggle="tooltip" title="{{ trans('admin.filter.delete') }}"
                               data-success="{{ trans('admin.'. $entity . '.delete') }}"data-question="{{ trans('admin.'. $entity . '.question_del') }}">
                            <i class="fa fa-trash-o"></i>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-muted">{{ trans('admin.users.nothing') }}</p>
@endif
