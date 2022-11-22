@php($is_international = $tour->route->is_international)
@php($is_taxi = $tour->route->is_taxi)
@if($orders->count())
    {{--@php($orders = \App\Repositories\SelectRepository::orderByStation($orders, $tour))--}}
    @php($orders = $orders->sortBy('station_from_time'))
    <div class="table-responsive">
        <br>
        <table class="table table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th style="width: 6%" class="text-center">{{ trans('admin_labels.status') }}</th>
                <th class="text-center">{{ trans('admin_labels.client_id') }}</th>
                <th></th>
                <th class="text-center">{{ trans('admin_labels.phone') }}</th>
                <th class="text-center">{{ trans('admin_labels.station_from_id') }}<br></th>
                <th class="text-center">{{ trans('admin_labels.date_start_time') }}</th>
                <th class="text-center">{{ trans('admin_labels.station_to_id') }}</th>
                {{--<th>{{ trans('admin_labels.date_finish_time') }}</th>--}}
                <th>{{ trans('admin_labels.tickets') }}</th>
                <th class="text-center">{{ trans('admin_labels.place') }}</th>
                @if($is_taxi)
                    <th class="text-center">{{ trans('admin_labels.price') }}</th>
                @endif
                <th class="text-center">{{ trans('admin_labels.appearance') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders->load('client', 'orderPlaces') as $order)
                @php($order_info = \App\Repositories\SelectRepository::getInfoOrder($order))
                <tr class="js_order_row_{{$order->id}}">
                    <td>
                         <span data-toggle="tooltip" title=""
                               data-original-title="{{$order_info}}">
                        <div class="checkbox m-n p-t-n">
                            {{ Form::checkbox('orders['. $order->id .']', $order->id, false, ['class' => 'js_checkbox', 'id' => 'orders['. $order->id .']']) }}
                            {{ Form::label('orders['. $order->id .']', $order->id) }}
                        </div>
                    </span>
                    <td class="text-center">
                        {{--{!! trans('pretty.statuses.'. $order->status ) !!}--}}
                        <span class="js_count_sms">
                                {{$order->cnt_sms}}
                        <i data-toggle="tooltip" title="{{trans('admin.orders.send_sms')}}" class="text-info fa fa-envelope"></i>
                        @if ($order->is_pay || $order->appearance)
                            <i data-toggle="tooltip" title="{{trans('admin.orders.paid')}}" class="text-info fa fa-money"></i>
                        @endif
                            @if (!$order->operator_id)
                                <i data-toggle="tooltip" title="{{trans('admin.orders.booked_from_website')}}" class="text-info fa fa-internet-explorer"></i>
                            @endif
                            @if ($order->comment)
                                <i data-toggle="tooltip" title="{{$order->comment}}" class="text-info fa fa-comment"></i>
                            @endif
                        </span>
                    </td>
                    <td style="width: 16%">
                        @if($order->client)
                            <span data-toggle="tooltip" title="{{$order->comment}}">{{ $order->client->last_name }} {{ $order->client->first_name }}</span>
                        @else
                            {{trans('admin.orders.not_confirmed')}}
                        @endif
                    </td>
                    <td>
                        {{ Form::checkbox('is_call', 1, $order->is_call, [
                                                    'class' => 'js_input_is_call',
                                                    'url'   => route('admin.orders.is_call'),
                                                    'id'    => $order->id,
                                                    ]
                            ) }} </td>
                    @if($order->client)
                    <td>
                        <div class="dropdown dropup">
                          <button class="btn btn-default text-success" style="border:0px;" 
                                data-toggle="dropdown">
                                <i class="text-success fa fa-phone"></i> {{ $order->client->phone ? $order->client->phone : '' }}
                          </button>
                          <ul class="dropdown-menu">
                            <li><a href="{{ route('admin.calls.out') }}/{{$order->client->phone}}">{{trans('admin.users.call')}}</a></li>
                            <li><a class="js_admin_send_actual_sms" data-url="{{route('admin.sms.send_actual_order')}}" data-id="{{$order->id}}">{{trans('admin.tours.send_sms')}}</a></li>
                          </ul>
                        </div>
                    </td>
                    @else <td></td>
                    @endif

                    <td style="width: 20%" class="text-center">{{ $order->stationFrom->name}}</td>
                    @if ($order->stationFrom->status  == 'collect')
                        <td>
                            <input class="form-control station_from_time" type="time" data-url="{{route('admin.orders.change_from_time')}}"
                                   data-id="{{$order->id}}" value="{{$order->station_from_time}}" step="180">
                        </td>
                    @else
                        <td class="text-center">{{ $order->station_from_time}}</td>
                    @endif
                    <td style="width: 20%" class="text-center">{{ $order->stationTo->name}}</td>
                    {{--<td>{{ $order->station_to_time}}</td>--}}
                    {{--<td>{{ $order->orderPlaces->implode('number', ', ') }}</td>--}}
                    <td class="text-center" style="width: 5%">
                        <span>
                        {{ $order->count_places }}
                        @if ($order->count_places > 1 && $is_international)
                            <a data-toggle="collapse" data-target="#order_places-{{$order->id}}" class="btn btn-default btn-xs">
                                <i class="glyphicon glyphicon-eye-open"></i>
                            </a>
                        @endif
                        </span>
                    </td>
                    @php($places = array())
                    @if ($order->count_places > 1 && !$is_international)
                        @php($orderPlaces = $order->orderPlaces)
                        @foreach($orderPlaces as $orderPlace)
                            @if ($loop->iteration == 1)  @continue; @endif
                            @php($places [] = $orderPlace->number)
                        @endforeach
                    @endif
                    <td class="text-center"><span data-toggle="tooltip" title="{{implode(',',$places)}}">{{isset($order->orderPlaces[0]->number)? $order->orderPlaces[0]->number : '-'}}</span></td>
                    @if($is_taxi)
                    <td class="text-center" style="width: 18%;">
                        <input data-order_id="{{$order->id}}" data-url="{{route('admin.orders.change_price')}}"
                               class="form-control js_input_order_price text-center" value="{{$order->price}}">
                    </td>
                    @endif
                    <td class="text-center">
                        {{--{!! trans('pretty.confirm.'. $order->confirm) !!}--}}
                        @if($noAppearanceCount = $order->orderPlaces->where('appearance', '===', 0)->count())
                            <span class="label label-danger">{{trans('admin.rent.absence')}}: {{ $noAppearanceCount }}</span>
                        @endif
                    </td>
                    <td class="text-center td-actions">
                        <a href="{{ route('admin.orders.edit', $order)}}" class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title="{{trans('admin.filter.edit')}}">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="{{route ('admin.orders.cancel', $order)}}" class="btn btn-sm btn-danger js_panel_confirm" data-toggle="tooltip" title="{{trans('admin.filter.cancel')}}" data-success="{{trans('admin.pulls.cancel_order')}}" data-question="{{trans('admin.pulls.question_cancel')}}">
                            <i class="fa fa-close"></i>
                        </a>
                        <a href="{{route ('admin.orders.delete_order', $order)}}" class="btn btn-sm btn-danger js_panel_confirm" data-toggle="tooltip" title="{{trans('admin.filter.delete')}}" data-success="{{trans('admin.pulls.del_order')}}" data-question="{{trans('admin.pulls.question_del')}}">
                            <i class="fa fa-trash-o"></i>
                        </a>
                    </td>
                </tr>
                @if ($order->count_places > 1 && $is_international)
                    @php($orderPlaces = $order->orderPlaces)
                    <tr class="accordian-body collapse" id="order_places-{{$order->id}}">
                              <td style="padding: 0px;" colspan="12" class="hiddenRow">

                                @foreach($orderPlaces as $orderPlace)
                                    @if ($loop->iteration == 1)  @continue; @endif
                                <div>
                                    <div style="padding-top: 7px;" class="col-md-1">
                                        <div>
                                            место:<h2><b>{{$orderPlace->number}}</b></H2>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div style="width: 95%;">
                                            {!! Form::panelText('last_name', $orderPlace->surname, null, ['class' => "form-control",
                                            'name' => 'order_places['.$orderPlace->id.'][surname]'], false) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div style="width: 95%;">
                                            {!! Form::panelText('first_name', $orderPlace->name, null, ['class' => "form-control",
                                            'name' => 'order_places['.$orderPlace->id.'][name]'], false) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div style="width: 95%;">
                                            {!! Form::panelText('patronymic', $orderPlace->patronymic, null, ['class' => "form-control",
                                            'name' => 'order_places['.$orderPlace->id.'][patronymic]'], false) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div style="width: 95%;">
                                            {!! Form::panelText('passport', $orderPlace->passport, null, ['class' => "form-control",
                                            'name' => 'order_places['.$orderPlace->id.'][passport]'], false) !!}
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                    <a data-order_id="{{$order->id}}" data-url="{{route('admin.orders.save_data_order_places')}}"
                                            class="col-md-offset-10 btn btn-primary save_order_places">
                                        {{trans('admin.filter.save')}}
                                    </a>
                            </td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-muted m-t-sm">{{trans('admin.pulls.order_not_found')}}</p>
@endif