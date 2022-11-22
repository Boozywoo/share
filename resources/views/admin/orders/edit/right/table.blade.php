@if($tours->count())
    <div class="table-responsive f-z-16">
        <table class="table table-condensed table-hover">
            <thead>
            <tr>
                <th></th>
                <th>{{ trans('admin_labels.schedule_id') }}</th>
                <th>{{ trans('admin_labels.time_start') }}</th>
                <th>{{ trans('admin_labels.route_id') }}</th>
                <th>{{ trans('admin_labels.bus_id') }}</th>
                <th class="text-center">{{ trans('admin_labels.free_places') }}</th>
                <th>{{ trans('admin_labels.pool')}}</th>
                <th>{{ trans('admin_labels.price') }}</th>
            </tr>
            </thead>
            <tbody>
            @php($isAgent = Auth::user()->isAgent)
            @php($isMediator = Auth::user()->isMediator)

            @foreach($tours as $tour)
            @if(($isAgent || $isMediator) && !$tour->is_show_agent) @continue @endif
            @php($freePlacesCount = env('FRAGMENTATION_RESERVED') ? $tour->ordersFreeCity(request('city_from_id'), request('city_to_id')) : $tour->freePlacesCount)
            @php($freePlacesCount = $freePlacesCount > 0 ? $freePlacesCount : 0)
                <tr>
                    {{--@php($freePlacesCount = $tour->freePlacesCount)--}}
                    {{--@php($freePlacesCount = $tour->ordersFreeCity(request('city_from_id'), request('city_to_id')))--}}
                    
                    <td class="td-actions">
                        @if($freePlacesCount)
                                @if($tour->comment)
                                    <i class="fa fa-comment" data-toggle="tooltip" title="{{ $tour->comment }}"></i>
                                @endif
                            <span data-url="{{ route('admin.orders.toTour', [$tour, $order]) }}"
                                  data-city_from_id="{{request('city_from_id')}}"
                                  data-city_to_id="{{request('city_to_id')}}"
                                  data-toggle="title"
                                  data-tour_id="{{$tour->id}}"
                                  data-title="Перейти в рейс"
                                  class="btn btn-sm btn-primary js_orders-toTour">
                                @if($tour->reservation_by_place==1)<i class="fa fa-plus-place"></i>
                                @else
                                    <i class="fa fa-plus"></i>
                                @endif
                            </span>
                        @endif
                    </td>
                    <td>
                        {{ $tour->schedule_id }}
                    </td>
                    <td>{{ $tour->time_start }}</td>
                    <td>{{ $tour->route->name }}</td>
                    <td>{{ $tour->bus->name }} <span class="font-bold">{{ $tour->bus->places }} мест</span></td>
                    <td class="text-center {{ $freePlacesCount < 4 ? 'text-danger' : '' }} {{ $freePlacesCount == 0 ? 'font-bold' : '' }}">{{ $freePlacesCount }}</td>
                    <td>{{ $tour->ordersPullReserve->sum('count_places') }}</td>
                    @if($isMediator)
                    <td>{{ $tour->price + (isset($userRoutes[$tour->route->id]) ? $userRoutes[$tour->route->id]->pivot->added_price : 0)}} {{ trans('admin_labels.currencies_short.'.$tour->route->currency->alfa) }}</td>

                    @else
                    <td>{{ $tour->price }} {{ trans('admin_labels.currencies_short.'.$tour->route->currency->alfa) }}</td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-muted">{{ trans('admin.users.nothing') }}</p>
@endif
