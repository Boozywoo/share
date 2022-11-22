<div class="js_bus-wrap backg">
    <div class="text-center">
        @if($countPlaces = $order->orderPlaces->count())
            <label>{{trans('index.schedules.selected')}}
                : {{$countPlaces}} {{Lang::choice('место|места|мест', $countPlaces)}}</label> <br>
        @else
            <label>{{trans('index.schedules.sel_num_seats')}}</label> <br>
        @endif
        @php($countShow = 0)
        @php($freePlacesCount = $tour->freePlacesCount + $countPlaces)
        @if ($freePlacesCount >= $setting->limit_order_by_count)
            @php($countShow = $setting->limit_order_by_count)
        @elseif($order && $order->orderPlaces && $order->orderPlaces->count() >= $freePlacesCount)
            @php($countShow = $order->orderPlaces->count() + $freePlacesCount)
            @if ($countShow >= $setting->limit_order_by_count)
                @php($countShow = $setting->limit_order_by_count)
            @endif
        @else
            @php($countShow = $freePlacesCount)
        @endif
        {!! Form::selectRange('count_places', 1, $countShow ? $countShow : 1, isset($order) ? $order->count_places : '', ['class' => 'js_orders-count_places']) !!}
    </div>
    {!! Form::open(['route' => ['index.schedules.storePlaces', $tour], 'class' => 'js_ajax-form js_form-places bottomBusInfoBlock']) !!}
    <div class="js_form-places-inputs">
        @if($order->orderPlaces->count())
            @foreach($order->orderPlaces as $place)
                <input name="places[]" type="hidden" value="{{ $place->number }}">
            @endforeach
        @else
            {!! Form::hidden('places[]', '') !!}
        @endif
    </div>
    @if($order->id)
        <a href="{{ route('index.order.index') }}"
           class="continue js_form-places-btn js_btn js_set_stations">{{trans('index.schedules.continue')}}</a>
    @else
        <span class="continue js_btn js_form-places-btn js_set_stations">{{trans('index.schedules.continue')}}</span>
    @endif
    @if($tour->bus->amenities)
        <div class="amenities">
            @foreach($tour->bus->amenities as $amenity)
                <div class="amenity">
                    <img src="{{$amenity->mainImage->load()}}" width="14" alt="{{$amenity->name}}">
                </div>
            @endforeach
        </div>
    @endif
    <br style="clear:both"/>
    {!! Form::close()!!}
</div>
<div class="js_bus-overlay"></div>
