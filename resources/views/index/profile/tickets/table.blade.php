<table class="userRoutsShedule {{ $type }}">
    <colgroup>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
    </colgroup>
    <thead>
    <tr>
        <th>№</th>
        <th>{{ trans('index.profile.route')}}</th>
        <th>{{ trans('index.profile.date')}}</th>
        <th>{{trans('admin.orders.depart_time')}}</th>
        @if(env('PRINT')==true)
        <th class="print">{{trans('index.profile.ticket')}}</th>
        @endif
        <th>{{trans('index.home.cost')}}</th>
    </tr>
    </thead>
    <tbody>
    @if($orders->count())
        @foreach($orders as $order)
            <tr class="js_tickets-tr" data-id="{{ $order->id }}">
                <td>{{ $order->id }}</td>
                <td><a class="custom_link"
                       href="{{ route('index.profile.tickets.showOrder', $order) }}">{{ $order->tour->route->name }} </a>
                </td>
                <td>{{ ($order->from_date_time ?  $order->from_date_time : $order->tour->date_start)->format('d.m.Y') }}</td>
                <td>@time($order->station_from_time, 0)</td>
                <td class="print">
                    @if(env('PRINT')==true && $order->status == 'active' && $order->type == 'waiting')
                    <div class="print_link"><a
                                href="{{ route('index.profile.generatePDF', ["id"=>$order->id]) }}">{{ trans('index.profile.to_print') }}</a>
                    </div>
                    @endif
                    @if (auth()->user()->client->email && env("EMAIL")==true && $order->status == 'active' && $order->type == 'waiting')
                        <div class="print_link"><a
                                    class="js_send-to-email" href="javascript:;"
                                    data-order-id="{{$order->id}}">{{ trans('index.profile.to_email') }}</a>
                        </div>
                    @endif
                    @if (env('EKAM') == true && $order->appearance == 1)
                        <div class="print_link">
                            <a href="{{route('index.order.get_check', $order)}}">Смотреть чек</a>
                        </div>
                    @endif
                </td>
                <td class="withHiddenContent">
                    {{$order->totalPrice}} {{ trans('admin_labels.currencies_short.'.$order->tour->route->currency->alfa) }}
                    <br>
                    @if($setting->is_pay_on && $order->status == 'active' && $order->type == 'waiting')
                        @if($order->type_pay == 'success' || $order->type_pay == 'checking-account' || $order->is_pay == 1)
                            Билет
                            @if ($order->partial_prepaid) частично оплачен ({{ $order->prepaidHumanPrice }}) @else оплачен @endif
                        @else
                            <a href="{{route('index.order.payOrder', $order)}}">
                                <button class="to-pay">{{ trans('index.profile.to_pay') }}</button>
                            </a>
                        @endif
                    @endif
                </td>
                
                @if($type == 'upcoming')
                    @if($order->type_pay == 'success' || $order->type_pay == 'checking-account' || $order->is_pay == 1)
                    @else
                        @php($orderTime = new \Carbon\Carbon($order->tour->date_time_start))
                        @php($maxTime = \Carbon\Carbon::now())
                        @if($setting->order_cancel_time && $orderTime->subMinutes($setting->order_cancel_time) > $maxTime || !$setting->order_cancel_time)
                        <td><a class="removeRow" data-id="{{ $order->id }}">x</a></td>
                        @endif 
                    @endif
                @endif
                @if($type == 'done')
                    @if(isset($order->review))
                         <td><a class="giveReview" data-id="{{ $order->id }}" comment="{{ $order->review->comment }}" 
                            rating="{{ $order->review->rating }}">{{ trans('index.profile.feedback')}}</a></td>
                    @else
                         <td><a class="giveReview" data-id="{{ $order->id }}">{{ trans('index.profile.feedback')}}{{$order->review}}</a></td>
                    @endif
                @endif
                

            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="6" class="completedTrip">{{ trans('index.profile.not_found')}}</td>
        </tr>
    @endif
    </tbody>
</table>
<div id="js_email-popup"></div>
<div id="js_ticket-active" style="display: none;"></div>
