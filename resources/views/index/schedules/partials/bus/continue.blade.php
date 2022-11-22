@if($order->orderPlaces->count())
    <p class="pleaceNum">{{trans('index.order.seat')}} {{ $order->orderPlaces->implode('number', ', ') }}</p>
    <a href="{{ route('index.order.index') }}" class="continue js_btn js_form-places-btn js_set_stations">{{trans('index.schedules.continue')}}</a>
@endif