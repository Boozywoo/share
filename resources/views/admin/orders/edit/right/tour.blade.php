@if($tour && !$tour->rent)
    @if (request()->get('status') == \App\Models\Order::STATUS_RESERVE)
        @php($freePlacesCount = $tour->freePlacesCount)
    @else
        @if (config('app.FRAGMENTATION_RESERVED') && !empty($freeFragmentPlaces))
            @php($freePlacesCount = $freeFragmentPlaces)
        @elseif (!empty($cityFromId) && !empty($cityToId))
            @php($freePlacesCount = $tour->ordersFreeCity($cityFromId, $cityToId))
        @else
            @php($freePlacesCount = $tour->ordersFreeCity(request('city_from_id'), request('city_to_id')))
        @endif
    @endif
    @php($currency = (isset($tour) && $tour->route->currency) ? $tour->route->currency->alfa : (!empty($curr_back->alfa)?$curr_back->alfa:'BYN'))
    <div class="row">
        <div class="col-md-6">
            @if(($order->id))
                <h3 class="text-primary">{{trans('admin.orders.slug')}}: <b>{{ $order->slug }}</b></h3>
            @endif
                <b>{{trans('admin.orders.route')}}</b> <span>{{ $tour->route->name }}</span><br>
                <b>{{trans('admin.tours.tour')}}{!! trans('pretty.statuses.'. $tour->status ) !!}</b> 
                <span>{!! $tour->prettyTime !!}</span>
                &nbsp;<a href="{{ route ('admin.tours.show', $tour) }}" class="btn btn-xs btn-info">Перейти в рейс</a>
                <br>
                <b>{{trans('admin.buses.bus')}}</b> 
                <span>{{ $tour->bus->name }} {{ $tour->bus->number }}</span>
                <br>
                @if($tour->driver)
                    <b>{{trans('admin.drivers.driver')}}</b>
                    <span>{{ $tour->driver->full_name}}</span>
                    <br>
                @else
                    <b>{{trans('admin.orders.no_driver')}}</b><br>
                @endif

                <b class="text-success">{{trans('admin.orders.empty_seats')}}</b>
                <span>{{ $freePlacesCount }}</span>
                <br>
                <span data-url="{{ route('admin.orders.toTours', ['order' => $order, 'date' => $tour->date_start->format('d.m.Y'), 'route_id' => $tour->route_id]) }}"
                      class="btn btn-xs btn-info js_orders-to-tours">{{trans('admin.orders.change_order')}}</span> <br>
        </div>
        <div class="col-md-6">
            @if(isset($order) && isset($order->old_places) && count($order->old_places) && isset($order->old_places['places']))
                <b class="js_order-old-places">{{trans('admin.orders.previous_seats')}} :</b> <br>
                <span class="text-info">{{trans('admin.orders.seats_quantity')}} :</span> {{ $order->old_places['count_places'] }}
                <br>
                <span class="text-info">{{trans('admin.orders.num')}} :</span>
                @foreach($order->old_places['places'] as $place)
                    {{ $place['number'] }},
                @endforeach
                <br><span class="text-info">{{trans('admin.orders.cost')}} :</span> {{$order->old_places['price'] }} {{ trans('admin_labels.currencies_short.'.$currency) }}
                <br>
            @endif
        </div>
    </div>
    <div class="hr-line-dashed"></div>
    @if(($order && request('status') != \App\Models\Order::STATUS_RESERVE && $order->places_with_number) || $tour->reservation_by_place)
        @include('admin.orders.edit.right.bus')
    @else
        @if(!$order->id)
            <div class="js_orders-selection-places btn btn-xs btn-warning"
                 data-url="{{ route('admin.orders.toTour', [$tour, $order, 'selection_places' => true]) }}">{{trans('admin.orders.sel_seats')}}
            </div>
        @endif
        @if ($order && $order->tour && $tour->id == $order->tour->id && empty($freeFragmentPlaces))
            @php($countPlaces = $freePlacesCount + $order->count_places)
        @else
            @php($countPlaces = $freePlacesCount)
        @endif
        @if($order && $order->type_pay == 'success')
            <div class="form-group">
                <label class="control-label">{{ trans('admin_labels.count_places') }}</label>
                <div><input class="form-control" type="text" value="{{ $order->count_places }}" disabled="disabled"></div>
                {!! Form::hidden('count_places', $order->count_places ?? 1, ['class' => "js_orders-count_places"]) !!}
            </div>
        @else
            {!! Form::panelRange('count_places', 1, $countPlaces ? $countPlaces : 1, isset($order) ? $order->count_places : '', null, ['class' => "form-control js_orders-count_places"], false) !!}
        @endif
    @endif

    <div class="hr-line-dashed"></div>
    @if($order && $order->id)
        <b>
            {{trans('admin.orders.departure_time')}} : @time($order->station_from_time, 0)
        </b>
        <span>
            {{ $order->stationFrom->city->FullTimezone }}
        </span>
        @if(!$order->tour->route->is_taxi)
            <div class="pull-right">
                <b>
                    {{trans('admin.orders.arrival_time')}} : @time($order->station_to_time, 0)
                </b>
                <span>
                    {{ $order->stationTo->city->FullTimezone }}
                </span>
            </div>
        @endif
        <div class="hr-line-dashed"></div>
        @if($order->coupon)
            <h3 class="font-bold">{{trans('admin.orders.promo_code')}}</h3>
            <p>{{ $order->coupon->name }} - <span class="font-bold">{{ $order->coupon->percent }}%</span></p>
        @endif
        @if ($order->count_places > 1 && $order->tour->route->discount_child  > 0)
            <span>{{trans('admin.orders.num_of_children')}}:</span>
            @php($countChildren = $order ? $order->orderPlaces->where('is_child',1)->count() : 0)
            {!! Form::selectRange('count_places', 0, $order->count_places - 1, $countChildren,
                    ['class' => 'js_admin_orders-count_places_child',
                    'data-url'=> route('admin.orders.children'), 'data-order_id'=> $order->id ]) !!}
        @endif
    @endif
    @if($order && $order->orderPlaces->count())
        <div class="js_admin_price_places">
            @include('admin.orders.edit.right.prices')
        </div>
    @endif
@else
    @include('admin.orders.edit.right.rent')
@endif