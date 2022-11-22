@if($orders->count())
    <div class="table-responsive">
        <h2>
            {{trans('admin.users.statistic')}}
            @if($orders->count())
                    <a href="{{$urlExcel}}">
                    <span data-toggle="modal" class="btn btn-sm btn-primary">
                        <i class="fa fa-file-excel-o"></i>
                    </span>
                </a>
            @endif
        </h2>
        <table class="table table-condensed">
            <thead>
            <tr>
                <th>№</th>
                <th>Дата поездки</th>
                <th>Тип оплаты</th>
                <th>Направление</th>
                <th>Стоимость</th>
                <th>Кол-во мест</th>
                <th>ФИО клиента</th>
                <th>Телефон клиента</th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->tour->date_start->format('Y-m-d') }}</td>
                    <td>{{ trans('admin.orders.pay_types.'.$order->type_pay) }}</td>
                    <td>{{ $order->tour->route->name }}</td>
                    <td>{{ $order->price }}</td>
                    <td>{{ $order->count_places }}</td>
                    <td>{{ $order->client ? $order->client->fullName : '' }}</td>
                    <td>{{ $order->client ? $order->client->phone : '' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-muted">{{trans('admin.users.nothing')}}</p>
@endif