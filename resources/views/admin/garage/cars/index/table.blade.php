@if($cars && $cars->count())
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('admin_labels.status') }}</th>
                <th>{{ trans('admin_labels.name') }}</th>
                <th>{{ trans('admin_labels.template_id') }}</th>
                <th>{{ trans('admin_labels.location_status') }}</th>
                <th>{{ trans('admin_labels.img') }}</th>
                <th>{{ trans('admin_labels.repair') }}</th>
                <th>{{ trans('admin_labels.km') }}</th>
                <th>{{ trans('admin_labels.fuel') }}</th>
                <th>{{ trans('admin_labels.cars_number') }}</th>
                <th>{{ trans('admin_labels.cars_model') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($cars as $car)
                <tr>
                    <td>{{ $car->id }}</td>

                    <td>
                        {!! trans('pretty.statuses.'. $car->status) !!}
                    </td>
                    <td>{{ $car->name }}</td>
                    <td>
                        <span data-url="{{route ('admin.buses.showPopup', $car)}}" data-toggle="modal"
                              data-target="#popup_tour-edit">
                            @include('admin.buses.templates.partials.template', ['template' => $car->template])
                        </span>
                    </td>
                    <td>
                        {{ __('admin.buses.location_statuses.'. $car->location_status) }}
                    </td>

                    <td>
                        @if($car->mainImage)
                            <div class="product__figure">
                                <img src="{{ $car->mainImage->load('admin', $car) }}">
                            </div>
                        @endif
                    </td>
                    <td>
                        @foreach($car->upcomingRepairs as $repair)
                            <span class="label label-danger">
                            {{ $repair->prettyDate }}
                        </span> <br>
                        @endforeach
                    </td>
                    <td>{{ $car->odometer }}</td>
                    <td>{{ $car->fuel }}</td>
                    <td>{{ $car->number }}</td>
                    <td>{{ $car->name }}</td>
                    <td class="td-actions">
                        @if($myCars->contains($car->id))
                            <a href="{{route ('admin.buses.diagnostic_cards.index', ['bus_id' => $car->id])}}"
                               class="btn btn-sm btn-warning pjax-link" data-toggle="tooltip"
                               title="{{ trans('admin.buses.diagnostic_card') }}">
                                <i class="fa fa-list-alt"></i>
                            </a>

                            @if($car->location_status == \App\Models\Bus::LOCATION_IN_GARAGE)
                                @if(checkCanBeUserTakenCar(auth()->id()))
                                <a href="{{route ('admin.'. $entity . '.take', [$car,'type' => \App\Models\DiagnosticCard::TYPE_TAKE])}}"
                                   class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip"
                                   title="{{ trans('admin.filter.take_car') }}">
                                    <i class="fa fa-key"></i>
                                </a>
                                @endif
                            @else
                                <a href="{{route ('admin.'. $entity . '.take', [$car,'type' => \App\Models\DiagnosticCard::TYPE_PUT])}}"
                                   class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip"
                                   title="{{ trans('admin.filter.put_car') }}">
                                    <i class="fa fa-home"></i>
                                </a>

                            @endif
                            @if($car->location_status !== \App\Models\Bus::LOCATION_ON_LINE)
                            <a href="{{route ('admin.buses.diagnostic_cards.create', [$car,'type' => \App\Models\DiagnosticCard::TYPE_REVIEW])}}"
                               class="btn btn-sm btn-primary" data-toggle="tooltip"
                               title="{{ trans('admin.filter.inspect_car') }}">
                                <i class="fa fa-search"></i>
                            </a>
                            @endif
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