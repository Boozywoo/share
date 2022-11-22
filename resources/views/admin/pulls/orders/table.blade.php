@if($orders->count())
    <div class="table-responsive">
        <table class="table table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('admin_labels.status') }}</th>
                <th>{{ trans('admin_labels.date_start') }}</th>
                <th>{{ trans('admin_labels.tour_id') }}</th>
                <th>{{ trans('admin_labels.client_id') }}</th>
                <th>{{ trans('admin_labels.tickets') }}</th>
                <th>{{ trans('admin_labels.sum') }}</th>
{{--                <th>{{ trans('admin_labels.payment_type') }}</th>--}}
                <th>{{ trans('admin_labels.confirm') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                <tr class="{{ $order->pull ? $bgWarning : '' }}">
                    <td>{{ $order->number }}</td>
                    <td>{!! trans('pretty.statuses.'. $order->status ) !!}</td>
                    <td>@date($order->tour->date_start)</td>
                    <td>
                        {{ $order->tour->prettyTimeStart }}
                    </td>
                    <td>
                        @if($order->client)
                            {{ $order->client->first_name }} <br>
                            @phone($order->client->phone)
                        @else
                            {{trans('admin.pulls.question_cancel')}}
                        @endif
                    </td>
                    <td>{{ $order->count_places }}</td>
                    <!-- <td nowrap>@price($order->totalPrice)</td> -->
                    <td nowrap>{{ number_format($order->totalPrice, 2, ',', ' ')}} {{ trans('admin_labels.currencies_short.'.$order->tour->route->currency->alfa) }}</td>
                    <td>{!! trans('pretty.confirm.'. $order->confirm) !!}</td>
                    <td class="td-actions">
                        <a href="{{ route('admin.orders.edit', $order)}}" class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title="{{trans('admin.filter.edit')}}">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="{{route ('admin.orders.cancel', $order)}}" class="btn btn-sm btn-danger js_panel_confirm js_update-filter" data-toggle="tooltip" title="Отменить" data-success="{{trans('admin.pulls.cancel_order')}}" data-question="{{trans('admin.pulls.question_cancel')}}">
                            <i class="fa fa-close"></i>
                        </a>
                        <a href="{{route ('admin.orders.delete_order', $order)}}" class="btn btn-sm btn-danger js_panel_confirm" data-toggle="tooltip" title="{{trans('admin.filter.delete')}}" data-success="{{trans('admin.pulls.del_order')}}" data-question="{{trans('admin.pulls.question_del')}}">
                            <i class="fa fa-trash-o"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-muted">{{ trans('admin.users.nothing') }}</p>
@endif
