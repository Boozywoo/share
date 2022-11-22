<div class="purse">
    @php($currency = (isset($tour) && $tour->route->currency) ? $tour->route->currency->alfa : (!empty($curr_back->alfa)?$curr_back->alfa:'BYN'))
    @foreach($order->orderPlaces as $key => $place)
        <div class="panel panel-primary">
            <div class="panel-heading">
                {{ trans('index.profile.ticket') }}: {{ $key + 1 }} @if ($place->is_child) (Детский) @endif <br>
                @if ($place->number)
                    {{ trans('admin.orders.num') }}: {{ $place->number }}
                @endif
                {{ trans('admin.clients.final_price') }}: <span
                        class="label label-warning">{{$place->price}} {{ trans('admin_labels.currencies_short.'.$order->tour->route->currency->alfa) }}</span>
            </div>
            <div class="panel-body">
                @if($place->sales->count() && !$place->is_child)
                    <!-- <h4 class="font-bold">{{ trans('index.home.sales') }}</h4> -->
                    <table class="table table-ticket">
                        </thead>
                        <tbody>
                            @foreach($place->sales as $sale)
                            <tr>
                                <td colspan="2">{{ $sale->name }}</td>
                            </tr>
                            <tr>
                                <td>{{ trans('admin.orders.price') }}</td>
                                <td>{{ $sale->pivot->old_price }} {{ trans('admin_labels.currencies_short.'.$currency) }}</td>
                            </tr>
                            <tr>
                                <td>{{trans('admin_labels.discount')}}</td>
                                <td>{{ $sale->value }}
                                    @if ($sale->is_percent > 0)
                                    %
                                    @else
                                    {{ trans('admin_labels.currencies_short.'.$currency) }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>{{ trans('admin.buses.total') }}</td>
                                <td>{{ $sale->pivot->new_price }} {{ trans('admin_labels.currencies_short.'.$currency) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
                @if($place->status_id)
                    <h4 class="font-bold">{{ trans('admin.clients.social') }}</h4>
                    <table class="table">
                        <thead>
                        <tr>
                            <td>{{ trans('admin.orders.name') }}</td>
                            <td>{{ trans('admin.orders.rolling_price') }}</td>
                            @if ($place->socialStatus->is_percent > 0)
                                <td>{{trans('admin_labels.percent')}}</td>
                            @else
                                <td>{{trans('admin_labels.discount')}}</td>
                            @endif
                            <td>{{ trans('admin.orders.new_price') }}</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ $place->socialStatus->name }}</td>
                            <td>{{ $place->status_old_price }}</td>
                            @if ($place->socialStatus->is_percent >0 )
                                <td><span class="font-bold">{{ $place->socialStatus->percent }}%</span></td>
                            @else
                                <td><span class="font-bold">{{ $place->socialStatus->value }} {{ trans('admin_labels.currencies_short.'.$currency) }}</span></td>
                            @endif
                            <td>{{ $place->price }}</td>
                        </tr>
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    @endforeach
</div>
@if($order->coupon)
    <h3 class="font-bold">Промокод {{ $order->coupon->name }} <span
                class="font-bold">{{ $order->coupon->percent }}%</span></h3>
@endif