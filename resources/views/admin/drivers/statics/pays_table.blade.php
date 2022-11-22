@if($filteredOrders->count())
    <div class="table-responsive">
        <h2>
            Начисления за брони
        </h2>
        <table class="table table-condensed">
            <thead>
            <tr>
                <th>{{trans('admin.users.num')}}</th>
                <th>{{trans('admin.orders.date_of_creation')}}</th>
                <th>{{trans('admin.orders.date_of_travel')}}</th>
                <th>{{trans('admin.users.sum')}}</th>
                <th>{{trans('admin.orders.clients_name')}}</th>
                <th>{{trans('admin.orders.clients_phone')}}</th>
                <th>{{trans('admin.orders.seats_quantity')}}</th>
                <th>{{trans('admin.orders.pay')}}</th>
                <th>{{ trans('index.home.landing_place') }}</th>
                <th>{{ trans('index.home.disembarkation_place') }}</th>
                <th>{{trans('admin.users.route')}}</th>
                <th class="text-center">{{ trans('admin_labels.appearance') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($filteredOrders as $order)
                @if(isset($order->client))
                <tr>
                    <td>
                        {{ $order->slug }}
                    </td>
                    <td>
                        {{ $order->created_at->format('Y-m-d') }}
                    </td>
                    <td>
                        {{ $order->tour->date_start->format('Y-m-d') }}
                    </td>
                    <td>
                        {{ $order->price . ' ' . trans('admin_labels.currencies_short.' . $order->currency->alfa) }}
                    </td>
                    <td>
                        {{ $order->client->FullName }}
                    </td>
                    <td>
                        {{ $order->client->phone }}
                    </td>
                    <td>
                        {{ $order->count_places }}
                    </td>
                    <td>
                        {{ $orderSum[$order->id]['sum'] . ' ' . trans('admin_labels.currencies_short.' . $orderSum[$order->id]['currency']->alfa) }}
                    </td>
                    <td>
                        {{ $order->stationFrom->city->name }} <br> {{ $order->stationFrom->name }}
                    </td>
                    <td>
                        {{ $order->stationTo->city->name }} <br> {{ $order->stationTo->name }}
                    </td>
                    <td style="text-align: left">
                        {{$order->tour->route_id ? $order->tour->route->name: ''}}
                    </td>
                    <td>
                        @if($noAppearanceCount = $order->orderPlaces->where('appearance', '===', 0)->count())
                            <span class="label label-danger">{{trans('admin.tours.absence')}}: {{ $noAppearanceCount }}</span>
                        @endif
                        @if($AppearanceCount = $order->orderPlaces->where('appearance', '===', 1)->count())
                            <span class="label label-primary">{{trans('admin.tours.presence')}}: {{ $AppearanceCount }}</span>
                        @endif
                    </td>
                </tr>
                @endif
            @endforeach
            </tbody>
        </table>
        <h2>Итого</h2>
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th style="width: 200px">Кол-во броней</th>
                    <th>Сумма</th>
                    <th>Выплаты</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ $filteredOrders->count() }}</td>
                <td>
                    @foreach($totalPrice as $alfa => $price)
                        <p>{{ $price . ' ' . trans('admin_labels.currencies_short.' . $alfa) }}</p>
                    @endforeach
                </td>
                <td>
                    @foreach($totalSum as $alfa => $price)
                        <p>{{ $price . ' ' . trans('admin_labels.currencies_short.' . $alfa) }}</p>
                    @endforeach
                </td>
            </tr>
            </tbody>
        </table>
    </div>
@else
    <p class="text-muted">{{ trans('admin.users.nothing') }}</p>
@endif
