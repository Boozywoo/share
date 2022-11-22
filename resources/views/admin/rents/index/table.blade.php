@if($tours->count())
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th nowrap>{{ trans('admin_labels.date_start') }}</th>
                <th nowrap>{{ trans('admin_labels.time_start') }}</th>
                <th nowrap>{{ trans('admin_labels.duration') }}</th>
                <th nowrap>{{ trans('admin_labels.type') }}</th>
                <th nowrap>{{ trans('admin_labels.city_from_id') }}</th>
                <th nowrap>{{ trans('admin_labels.city_to_id') }}</th>
                <th>{{ trans('admin_labels.bus_id') }}</th>
                <th>{{ trans('admin_labels.price') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($tours as $tour)
                @php($freePlacesCount = $tour->freePlacesCount)
                <tr class="{{ $tour->id == request('tour_id') ? $bgWarning : '' }}">
                    <td nowrap class="td-actions">
                        @if(!$tour->rent->is_full_data)
                            {!! trans('pretty.statuses.empty_data') !!}
                        @endif
                        @if ($tour->bus)
                            @if($tour->orders->count())
                                @php($order=$tour->orders->first())
                                <a href="{{ route('admin.orders.edit', $order)}}"
                                   class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip"
                                   title="Редактировать бронь">
                                    <i class="fa fa-edit"></i>
                                </a>
                            @else
                                <a href="{{ route('admin.orders.create',
                            [
                                'order' => '',
                                'tour_id' => $tour,
                                'route_id' => $tour->route_id,
                                'date' => $tour->date_start->format('d.m.Y'),
                            ]) }}" data-toggle="title" data-title="Перейти в рейс"
                                   class="btn btn-sm btn-primary pjax-link">
                                    <i class="fa fa-plus"></i>
                                </a>
                            @endif
                        @endif

                    </td>
                    <td>
                        {{ $tour->prettyDateStart }}
                    </td>
                    <td>
                        {{ $tour->prettyTimeStart }}
                    </td>
                    <td>
                        @if ($tour->rent && $tour->rent->tariff && $tour->rent->tariff->type == \App\Models\Tariff::TYPE_DURATION)
                            {{ $tour->rent->durationShow }}
                        @elseif ($tour->rent && $tour->rent->tariff && $tour->rent->tariff->type == \App\Models\Tariff::TYPE_DISTANCE)
                            {{ $tour->rent->distance }} км
                        @endif

                    </td>
                    <td>
                        @if ($tour->rent && $tour->rent->tariff && $tour->rent->tariff->type == \App\Models\Tariff::TYPE_DURATION)
                            По времени
                        @elseif ($tour->rent && $tour->rent->tariff && $tour->rent->tariff->type == \App\Models\Tariff::TYPE_DISTANCE)
                            По расстоянию
                        @endif

                    </td>
                    <td>
                        <span class="text" data-toggle="tooltip" title=""
                              data-original-title="{{$tour->rent->address}}">
                                {{$tour->rent && $tour->rent->fromCity ? $tour->rent->fromCity->city->name . ', ' . $tour->rent->fromCity->name : $tour->rent->address}}
                            </span>
                    </td>
                    <td>
                        <span class="text" data-toggle="tooltip" title=""
                              data-original-title="{{$tour->rent->address_to}}">
                                {{$tour->rent && $tour->rent->toCity ? $tour->rent->toCity->city->name . ', ' . $tour->rent->toCity->name : $tour->rent->address_to}}
                            </span>
                    </td>
                    @php($busPlaces = $tour->bus ? $tour->bus->places : 0 )
                    <td>@if($tour->bus)
                            {{ $tour->bus->name }} {{ $tour->bus->number }}
                        @else
                            <b>Автобус не назначен</b>
                        @endif
                        <span class="font-bold">
                            @if($busPlaces)
                                {{ $tour->bus->places }} мест
                            @endif
                        </span>
                        {!! trans('pretty.tours.types.'. $tour->type_driver ) !!}
                    </td>
                    <td>
                        <span data-toggle="tooltip" title="" data-original-title="{{$tour->statisticPlaces['amount']}}">
                                @price($tour->price)
                            </span>
                    </td>
                    <td class="td-actions">
                        @if (!Auth::user()->isMethodist)
                            <span data-url="{{route ('admin.' . $entity . '.showPopup', $tour)}}" data-toggle="modal"
                                    data-target="#popup_tour-edit"
                                    class="btn btn-sm btn-primary">
                                <i class="fa fa-edit"></i>
                            </span>
                            <a href="{{route ('admin.tours.show', $tour)}}"
                               class="btn btn-sm btn-warning pjax-link">
                                <i class="fa fa-eye"></i>
                            </a>

                            @if($tour->is_edit && $tour->ordersReady()->count())
                                <span data-url="{{route ('admin.tours.sendSmsPopup', $tour)}}" data-toggle="modal"
                                      data-target="#popup_tour-edit"
                                      class="btn btn-sm btn-info">
                                <i class="fa fa-envelope-o"></i>
                            </span>
                            @endif
                            <span data-url="{{route ('admin.tours.copy', $tour)}}" data-toggle="modal"
                                  data-target="#popup_tour-edit"
                                  class="btn btn-sm btn-success">
                                <i class="fa fa-copy"></i>
                            </span>
                            <a href="{{route ('admin.' . $entity . '.delete', $tour)}}"
                               class="btn btn-sm btn-danger js_panel_confirm" data-toggle="tooltip"
                               title="{{ trans('admin.filter.delete') }}">
                                <i class="fa fa-trash-o "></i>
                            </a>
                    </td>
                    @else
                        <a href="{{route ('admin.tours.show', $tour)}}"
                           class="btn btn-sm btn-warning pjax-link">
                            <i class="fa fa-eye"></i>
                        </a>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-muted">{{ trans('admin.users.nothing') }}</p>
@endif