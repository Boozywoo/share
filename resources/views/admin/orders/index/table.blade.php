<style>
    .tooltip-inner {
        white-space: pre-wrap;
        max-width: 470px;
        font-size: 13px;
    }
    .no-border td {
        border-top: none !important;
    }
</style>
@php($isAgent = Auth::user()->isAgent)
@php($isMediator = Auth::user()->isMediator)

<div class="row">
    <div class="col-sm-3">
        <form class="btn btn-sm btn-primary js_import">
            {!! Form::file('file', ['data-url' => route('admin.'.$entity.'.import')]) !!}
            <a href="" class="label btn-primary">{{ trans('admin.'.$entity.'.import') }}</a>
        </form>
    </div>
    @if($orders->count())

        @if(!$isAgent && !$isMediator)
            <div class="col-sm-3">
                <a class="hidden" id="tour-date-all" href="{{route('admin.orders.export').'?date='.request('date', date('d.m.Y'))}}"></a>
                <a class="hidden" id="tour-date-pay" href="{{route('admin.orders.export').'?date='.request('date', date('d.m.Y')).'&payed=1'}}"></a>
                <a href="#" class="js_panel_choice" data-title="{{trans('admin.orders.upcoming')}}" data-date-type="tour">
                    <h2>
                        <span data-toggle="modal" class="btn btn-sm btn-primary">
                        {{trans('admin.orders.upcoming')}} <i class="fa fa-file-excel-o"></i>
                        </span>
                    </h2>
                </a>
            </div>
            <div class="col-sm-3">
                <a class="hidden" id="created-date-all" href="{{route('admin.orders.export').'?type=created_date&date='.request('date', date('d.m.Y'))}}"></a>
                <a class="hidden" id="created-date-pay" href="{{route('admin.orders.export').'?type=created_date&date='.request('date', date('d.m.Y')).'&payed=1'}}"></a>
                <a href="#" class="js_panel_choice" data-title="{{trans('admin.orders.created')}}" data-date-type="created">
                    <h2>

                        <span data-toggle="modal" class="btn btn-sm btn-primary">
                            {{trans('admin.orders.created')}} <i class="fa fa-file-excel-o"></i>
                        </span>
                    </h2>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="#" data-title="{{trans('admin.orders.report')}}" data-date-type="created">
                    <h2>
                        <span data-url="{{route ('admin.' . $entity . '.printReport')}}" data-toggle="modal"
                              data-target="#popup_tour-edit" class="btn btn-sm btn-primary">{{trans('admin.orders.report')}} <i class="fa fa-file-excel-o"></i>
                        </span>
                    </h2>
                </a>
            </div>
        @endif
</div>




<div class="table-responsive">
    <table class="table table-condensed">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ trans('admin_labels.route_id') }}</th>
            <th>{{ trans('admin_labels.station_from_id') }}</th>
            <th>{{ trans('admin_labels.client_id') }}</th>
            <th>{{ trans('admin_labels.tickets') }}</th>
            <th>{{ trans('admin_labels.count') }}</th>
            <th>{{ trans('admin_labels.sum') }}</th>
            {{-- <th>{{ trans('admin_labels.payment_type') }}</th>--}}
            <th>{{ trans('admin_labels.confirm') }}</th>
            <th class="text-center">{{ trans('admin_labels.appearance') }}</th>
            {{--<th>{{ trans('admin_labels.created_at') }}</th>--}}
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr class="{{ $order->pull ? $bgWarning : '' }} {{ $order->return_order_id ? 'no-border' : '' }}">
                <td nowrap>
                    @php($order_info = \App\Repositories\SelectRepository::getInfoOrder($order))
                    <span data-toggle="tooltip" data-original-title="{{$order_info}}" data-html="true" data-delay="750" data-placement="auto">
                            @if(!empty($order->slug))
                            {{ $order->slug }}
                        @else
                            {{ $order->id }}
                        @endif
                        </span>
                    {!! trans('pretty.statuses.'. $order->status ) !!}
                    {{--                        @if ($order->operator_id)--}}
                    @if ($order->source=='client_app')
                        <i data-toggle="tooltip" title="{{trans('admin.orders.booked_from_mobile')}}"
                           class="text-info fa fa-tablet"></i>
                    @elseif ($order->source=='operator' && $order->operator ? $order->operator->hasRole('agent') : false)
                        <i data-toggle="tooltip" title="{{trans('admin.orders.booked_from_agent')}}"
                           class="text-success fa fa-user"></i>
                    @elseif ($order->source=='operator')
                    @elseif ($order->source=='driver')
                        <i data-toggle="tooltip" title="{{trans('admin.orders.booked_from_driver')}}"
                           class="text-info fa fa-bus"></i>
                    @else
                        <i data-toggle="tooltip" title="{{trans('admin.orders.booked_from_website')}}"
                           class="text-info fa fa-internet-explorer"></i>
                    @endif
                    @if ($statusPay = $order->StatusPay)
                        @if (env('ALT_PAYMENT'))
                            <span class="text-success" data-toggle="tooltip" title="{{ $order->altPayment }}"><i class="fa fa-money"></i></span>
                        @else
                            {!! trans('pretty.pay_statuses.'. $statusPay ) !!}
                        @endif
                    @endif
                    @if ($order->partial_prepaid)
                        <span class="text-warning" data-toggle="tooltip" title="Частичная оплата ({{ $order->prepaidHumanPrice }})"><i class="fa fa-star-half-o"></i></span>
                    @endif
                    @if ($order->comment || $order->flight_number)
                        <i data-toggle="tooltip" title="{{ $order->comment.' '.($order->flight_number ? trans('admin.orders.flight_number') : '').$order->flight_number }}" class="text-info fa fa-comment"></i>
                    @endif
                </td>
                <td>{{ $order->tour->route->name }}</td>
                <td nowrap>
                    @if($order->stationFrom)
                        {{ !$order->tour->route->is_taxi ? $order->stationFrom->city->name : '' }}
                        {{ $order->addressFrom ?? $order->stationFrom->name}} <br>
                    @endif
                    {{ $order->from_date_time ? $order->from_date_time->format('d.m.Y H:i') : $order->tour->date_start->format('d.m.Y').' '.$order->station_from_time  }}
                </td>
                <td nowrap>
                    @if($order->client)
                        {{ $order->client->last_name }}
                        {{ $order->client->first_name }} <br>
                        <div class="dropdown">
                            <button class="btn btn-default text-success " style="border:0px;"
                                    data-toggle="dropdown">
                                <i class="text-success fa fa-phone"></i>{{ $order->client->phone ? $order->client->phone : '' }}
                            </button>
                            <ul style="top: -220%" class="dropdown-menu">
                                {{--<li>
                                    <a href="{{ route('admin.calls.out') }}/{{$order->client->phone}}">{{trans('admin.users.call')}}</a>
                                </li>--}}
                                <li>
                                    <a href="tel:{{$order->client->phone}}">{{trans('admin.users.call')}}</a>
                                </li>
                                <li>
                                    <a class="js_admin_send_actual_sms" data-url="{{ route('admin.sms.send_actual_order') }}" data-id="{{$order->id}}">
                                        {{trans('admin.tours.send_sms')}}
                                    </a>
                                </li>
                                <li>
                                    <a data-url="{{ route ('admin.sms.individual_popup', $order) }}" data-toggle="modal" data-target="#popup_tour-edit">
                                        {{trans('admin.tours.send_sms_individual')}}
                                    </a>
                                </li>
                                <li>
                                    <a class="js_copy_to_clipboard" data-text="{{ route('index.ticket', $order->slug) }}">{{ trans('admin.tours.copy_ticket_link') }}</a>
                                </li>
                                @if ($order->pay_url)
                                    <li>
                                        <a class="js_copy_to_clipboard" data-text="{{ $order->pay_url }}">{{ trans('admin.tours.copy_pay_link') }}</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @else
                        {{trans('admin.pulls.client_not_create')}}
                    @endif
                </td>
                <td>{{ $order->orderPlaces->implode('number', ', ') }}</td>
                <td>{{ $order->count_places }}</td>
                {{--<td nowrap>@price($order->totalPrice)</td>--}}
                <td nowrap>
                    {{ number_format($order->totalPrice, 2, ',', ' ') }}
                    {{ trans('admin_labels.currencies_short.'.$order->tour->route->currency->alfa) }}
                </td>
                <td>{!! trans('pretty.confirm.'. $order->confirm) !!}</td>
                {{--<td>{{ $order->created_at }}</td>--}}
                <td class="text-center">
                    @if($noAppearanceCount = $order->orderPlaces->where('appearance', '===', 0)->count())
                        <span class="label label-danger">{{trans('admin.tours.absence')}}: {{ $noAppearanceCount }}</span>
                    @endif
                    @if($AppearanceCount = $order->orderPlaces->where('appearance', '===', 1)->count())
                        <span class="label label-primary">{{trans('admin.tours.presence')}}: {{ $AppearanceCount }}</span>
                    @endif
                </td>
                <td class="td-actions">
                    @if($order->status == \App\Models\Order::STATUS_ACTIVE)
                        @if($order->confirm)
                            @if(env('EKAM') == true)
                                <a data-url="{{ route('admin.orders.get_check', $order)}}"
                                   class="btn btn-sm btn-primary js_orders-get-check"
                                   title="Посмотреть чек"><i class="fa fa-file-text"></i>
                                </a>
                            @endif
                            @if(env('PRINT'))
                                @if(($isAgent || $isMediator) && $setting->is_pay_on && in_array($order->type_pay,[\App\Models\Order::TYPE_PAY_SUCCESS, \App\Models\Order::TYPE_PAY_CASH_PAYMENT]))
                                    <a href="{{ route('admin.orders.generate_pdf', $order)}}"
                                       class="btn btn-sm btn-primary"
                                       data-toggle="tooltip" title="{{trans('admin.orders.down')}}">
                                        <i class="fa fa-file-word-o"></i>
                                    </a>
                                @elseif(($isAgent || $isMediator) && $setting->is_pay_on)
                                    @if(!in_array($order->id, $returnOrders ?? []))
                                        <a target="_blank" href="{{ route('admin.orders.pay', $order)}}"
                                           class="btn btn-sm btn-primary"
                                           data-toggle="tooltip" title="{{trans('admin.orders.pay')}}">
                                            <i class="fa fa-money"></i>
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('admin.orders.generate_pdf', $order)}}"
                                       class="btn btn-sm btn-primary" target="_blank"
                                       data-toggle="tooltip" title="{{trans('admin.orders.down')}}">
                                        <i class="fa fa-file-word-o"></i>
                                    </a>
                                @endif
                            @endif
                            <a href="{{ route('admin.'. $entity . '.edit', $order)}}"
                               class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip"
                               title="{{trans('admin.filter.edit')}}">
                                <i class="fa fa-edit"></i>
                            </a>
                        @endif
                        <a href="{{route ('admin.' . $entity . '.cancel', $order)}}"
                           class="btn btn-sm btn-danger js_panel_confirm js_update-filter" data-toggle="tooltip"
                           title="{{trans('admin.filter.cancel')}}"
                           data-success="{{trans('admin.pulls.cancel_order')}}"
                           data-question="{{trans('admin.pulls.question_cancel')}}">
                            <i class="fa fa-close"></i>
                        </a>
                    @else
                        @if($order->confirm)
                            <a href="{{route ('admin.' . $entity . '.restore', $order)}}"
                               class="btn btn-sm btn-success js_panel_confirm js_update-filter" data-toggle="tooltip"
                               title="{{trans('admin.filter.restore')}}"
                               data-success="{{trans('admin.pulls.cancel_restore')}}"
                               data-question="{{trans('admin.pulls.question_restore')}}">
                                <i class="fa fa-undo"></i>
                            </a>
                        @endif
                    @endif

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@else
    <p class="text-muted">{{ trans('admin.users.nothing') }}</p>
@endif
