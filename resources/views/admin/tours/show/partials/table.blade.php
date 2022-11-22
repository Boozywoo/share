@if($tour->rent)
@php($is_international = true)
@php($is_taxi = false)
@else
@php($is_international = $tour->route->is_international)
@php($is_taxi = $tour->route->is_taxi)
@endif
@if($orders->count())
<style>
    #nav li li ul {
            position: absolute;
            top: 0;
            left: 100%;
        }
    </style>
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
                <th>{{ trans('admin_labels.price') }}</th>
                <th class="text-center">{{ trans('admin_labels.place') }}</th>
                @if($is_taxi)
                    <th class="text-center">{{ trans('admin_labels.price') }}</th>
                @endif
                <th class="text-center">{{ trans('admin_labels.appearance') }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @php($orders = $orders->load('client', 'orderPlaces','stationFrom','stationTo', 'smsLog'))
            @php($isAgent = Auth::user()->isAgent)
            @php($isMediator = Auth::user()->isMediator)

            @php($authId = Auth::id())
            @foreach($orders as $order)

            @if(($isAgent || $isMediator) && $order->operator_id != $authId) @continue @endif
            @php($order_info = \App\Repositories\SelectRepository::getInfoOrder($order))
            @php($order_sms = \App\Repositories\SelectRepository::getSMSOrder($order))
            <tr class="js_order_row_{{$order->id}}">
                <td>
                    <span data-toggle="tooltip" title="" data-html="true" data-original-title="{{$order_info}}">
                        <div class="checkbox m-n p-t-n">
                            @if($actions){{ Form::checkbox('orders['. $order->id .']', $order->id, false, ['class' => 'js_checkbox', 'id' => 'orders['. $order->id .']']) }}@endif
                            {{ Form::label('orders['. $order->id .']', $order->slug) }}
                        </div>
                    </span>
                <td class="text-center">

                    @php($smsLog = 'text-info')
                    @if($order->smsLog->where('status', \App\Models\SmsLog::SMS_STATUS_DELIVERED)->count() != $order->smsLog->count())
                        @php($smsLog = 'text-success')
                    @endif
                    <span class="js_count_sms">{{$order->cnt_sms}}
                        <span data-toggle="tooltip" title="" data-html="true" data-original-title="{{$order_sms}}">
                            <i data-toggle="tooltip" title="" class="fa fa-envelope {{$smsLog}}"></i>
                        </span>
                        @if ($statusPay = $order->StatusPay)
                            @if (env('ALT_PAYMENT'))
                                <span class="text-success" data-toggle="tooltip" title="{{ $order->altPayment }}"><i class="fa fa-money"></i></span>
                            @else
                                {!! trans('pretty.pay_statuses.'. $statusPay ) !!}
                            @endif
                        @endif
                        @if ($order->partial_prepaid)
                            <span class="text-warning" data-toggle="tooltip" title="Частичная оплата ({{ $order->prepaidHumanPrice }})">
                                <i class="fa fa-star-half-o"></i>
                            </span>
                        @endif
                        @if ($order->source=='client_app')
                            <i data-toggle="tooltip" title="{{trans('admin.orders.booked_from_mobile')}}" class="text-info fa fa-tablet"></i>
                        @elseif ($order->source=='operator' && $order->operator ? $order->operator->hasRole('agent') : false)
                            <i data-toggle="tooltip" title="{{trans('admin.orders.booked_from_agent')}}"
                                   class="text-success fa fa-user"></i>
                        @elseif ($order->source=='operator')
                        @elseif ($order->source=='driver')
                            <i data-toggle="tooltip" title="{{trans('admin.orders.booked_from_driver')}}" class="text-info fa fa-user-md"></i>
                        @else
                        <i data-toggle="tooltip" title="{{trans('admin.orders.booked_from_website')}}" class="text-info fa fa-internet-explorer"></i>
                        @endif
                        @if ($order->comment || $order->flight_number)
                            <i data-toggle="tooltip" title="{{ $order->comment.' '.($order->flight_number ? trans('admin.orders.flight_number') : '').$order->flight_number }}" class="text-info fa fa-comment"></i>
                        @endif
                    </span>
                </td>
                <td style="width: 16%">
                    @if ($order->client)
                        <span data-toggle="tooltip" title="{{$order->comment}}">{{ $order->client->last_name }} {{ $order->client->first_name }}</span>
                    @else
                        {{trans('admin.orders.not_confirmed')}}
                    @endif
                </td>
                <td>
                    @if($order->client && $order->client->status != \App\Models\Client::STATUS_SYSTEM)
                    <div class="checkbox">
                        {{ Form::checkbox('is_call', 1, $order->is_call, [
                        'class' => 'js_input_is_call checkbox',
                        'url' => route('admin.orders.is_call'),
                        'id' => $order->id,
                        'phone' => $order->client ? $order->client->phone : '',
                        ])
                        }}
                        <label for="{{$order->id}}"></label>
                    </div>
                    @endif
                </td>
                @if($order->client && $order->client->status != \App\Models\Client::STATUS_SYSTEM)
                    <td>
                        <div class="dropdown dropup">
                            <button class="btn btn-default text-success" style="border:0px;" data-toggle="dropdown">
                                <i class="text-success fa fa-phone"></i> {{ $order->client->phone ? $order->client->phone : '' }}
                            </button>
                            <ul style="bottom:-20%" class="dropdown-menu">
                                {{--<li>
                                    <a href="{{ route('admin.calls.out') }}/{{$order->client->phone}}">{{trans('admin.users.call')}}</a>
                                </li>--}}
                                <li>
                                    <a href="tel:{{$order->client->phone}}">{{trans('admin.users.call')}}</a>
                                </li>
                                <li><a class="js_admin_send_actual_sms" data-url="{{route('admin.sms.send_actual_order')}}" data-id="{{$order->id}}">
                                        {{trans('admin.tours.send_sms')}}
                                    </a>
                                </li>
                                <li>
                                    <a data-url="{{route ('admin.sms.individual_popup', $order)}}" data-toggle="modal" data-target="#popup_tour-edit">
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
                    </td>
                @else
                    <td></td>
                @endif
                <td style="width: 20%" class="text-center">
                    @if(!$order->tour->rent && $order->stationFrom)
                        {{ !$tour->route->is_taxi ? $order->stationFrom->city->name : '' }}
                        {{ $order->addressFrom ?? $order->stationFrom->name}}
                        @if (($order->stationFrom->status == 'collect') OR ($setting->edit_departure_all_stations && in_array($order->stationFrom->status, ['active', 'taxi'])))
                            <td>
                                <input class="form-control station_from_time" type="time" data-url="{{route('admin.orders.change_from_time')}}" data-id="{{$order->id}}" value="{{$order->station_from_time}}" step="180">
                            </td>
                        @else
                            <td class="text-center">{{ $order->station_from_time}}</td>
                        @endif
                    @elseif ($order->tour->rent && $order->tour->rent->fromCity)
                        <span class="text" data-toggle="tooltip" title="" data-original-title="{{$tour->rent->address}}">
                            {{$order->tour->rent && $order->tour->rent->fromCity ? $order->tour->rent->fromCity->name : ''}}
                        </span>
                    @endif
                </td>

                <td style="width: 20%" class="text-center">
                    @if(!$order->tour->rent && $order->stationTo)
                        {{ !$tour->route->is_taxi ? $order->stationTo->city->name : ''}}
                        {{ $order->addressTo ?? $order->stationTo->name}}
                    @elseif ($order->tour->rent && $order->tour->rent->toCity)
                        <span class="text" data-toggle="tooltip" title="" data-original-title="{{$tour->rent->address_to}}">
                            {{$order->tour->rent && $order->tour->rent->toCity ? $order->tour->rent->toCity->name : ''}}
                        </span>
                    @endif
                </td>
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
                @if ($loop->iteration == 1) @continue; @endif
                @php($places [] = $orderPlace->number)
                @endforeach
                @endif
                <td class="text-center">
                    {{$order->price}} {{ isset($tour->route) ? trans('admin_labels.currencies_short.' . $tour->route->currency->alfa) : 'BYN' }}
                    @if($countReturnDiscount = $order->orderPlaces->where('is_return_ticket', true)->count())
                    <span data-toggle="tooltip" title="Скидка на обратный билет - {{$order->tour->route->discount_return_ticket}}
                                  {{ $order->tour->route->discount_return_ticket_type ? '%' : '' }} на {{$countReturnDiscount}} шт.">
                        {!! trans('pretty.return-ticket') !!}
                    </span>
                    @endif
                </td>
                <td class="text-center">
                    <span>
                        {{implode(',', $order->orderPlaces->pluck('number')->toArray())}}
                    </span>
                </td>
                @if($is_taxi)
                <td class="text-center" style="width: 18%;">
                    <input data-order_id="{{$order->id}}" data-url="{{route('admin.orders.change_price')}}" class="form-control js_input_order_price text-center" value="{{$order->price}}">
                </td>
                @endif
                <td class="text-center">
                    {{--{!! trans('pretty.confirm.'. $order->confirm) !!}--}}
                    @if($noAppearanceCount = $order->orderPlaces->where('appearance', '===', 0)->count())
                        <span class="label label-danger">{{trans('admin.tours.absence')}}: {{ $noAppearanceCount }}</span>
                    @endif
                    @if($AppearanceCount = $order->orderPlaces->where('appearance', '===', 1)->count())
                        <span class="label label-primary">{{trans('admin.tours.presence')}}: {{ $AppearanceCount }}</span>
                    @endif
                </td>
                <td class="text-center td-actions">
                    @if($actions)
                        @if($order->client && $order->client->status != \App\Models\Client::STATUS_SYSTEM)
                            <a href="{{ route('admin.orders.edit', $order)}}" class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title="{{trans('admin.filter.edit')}}">
                                <i class="fa fa-edit"></i>
                            </a>
                            @if(env('PRINT'))
                                @if(($isAgent || $isMediator) && $setting->is_pay_on && in_array($order->type_pay,[\App\Models\Order::TYPE_PAY_SUCCESS, \App\Models\Order::TYPE_PAY_CASH_PAYMENT]))
                                    <a target="_blank" href="{{ route('admin.orders.pdf', $order)}}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="{{trans('admin.orders.down')}}">
                                        <i class="fa fa-file-word-o"></i>
                                    </a>
                                @elseif(($isAgent || $isMediator) && $setting->is_pay_on)
                                    <a target="_blank" href="{{ route('admin.orders.pay', $order)}}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="{{trans('admin.orders.pay')}}">
                                        <i class="fa fa-money"></i>
                                    </a>
                                @else
                                    <a target="_blank" href="{{ route('admin.orders.generate_pdf', $order)}}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="{{trans('admin.orders.down')}}">
                                        <i class="fa fa-file-word-o"></i>
                                    </a>
                                @endif
                            @endif
                            @if ($order->latitude && $order->longitude)
                                <a href="https://yandex.ru/maps/?pt={{ $order->longitude }},{{ $order->latitude }}&z=17&l=map" class="btn btn-sm btn-primary" target="_blank" data-toggle="tooltip" title="{{trans('admin.tours.map')}}">
                                    <i class="fa fa-map-marker"></i>
                                </a>
                            @endif
                            @if ($tour->route->is_taxi)
                                <a href="{{ route('admin.tours.build_taxi_route', $order) }}" class="btn btn-sm btn-primary" target="_blank" data-toggle="tooltip" title="{{trans('admin.tours.map')}}">
                                    <i class="fa fa-map-marker"></i>
                                </a>
                            @endif
                            <a href="{{route ('admin.orders.cancel', $order)}}" class="btn btn-sm btn-danger js_panel_confirm" data-toggle="tooltip" title="{{trans('admin.filter.cancel')}}" data-reload="true" data-success="{{trans('admin.pulls.cancel_order')}}" data-question="{{trans('admin.pulls.question_cancel')}}">
                                <i class="fa fa-close"></i>
                            </a>
                        @endif
                    @endif
                    
                    @if($order->confirm && $order->status == \App\Models\Order::STATUS_DISABLE)   
                            <a href="{{route ('admin.orders.restore', $order)}}"
                            class="btn btn-sm btn-success js_panel_confirm js_update-filter" data-toggle="tooltip"
                            title="{{trans('admin.filter.restore')}}"
                            data-success="{{trans('admin.pulls.cancel_restore')}}"
                            data-question="{{trans('admin.pulls.question_restore')}}">
                                <i class="fa fa-undo"></i>
                            </a>
                    @endif
                </td>
            </tr>
            @if ($order->count_places > 1 && $is_international)
            @php($orderPlaces = $order->orderPlaces)
            <tr class="accordian-body collapse" id="order_places-{{$order->id}}">
                <td colspan="12" class="hiddenRow">
                    @foreach($orderPlaces as $orderPlace)
                        @if ($loop->iteration == 1) 
                            <div>
                                <div style="padding: 25px;" class="col-md-1">
                                    <div>
                                        место:<h2><b>{{$orderPlace->number}}</b></H2>
                                    </div>
                                </div>
                                @foreach ($order->tour->route->textInputs as $input)
                                    <div class="col-md-2">
                                        <div style="width: 90%;">
                                            {!! Form::panelText($input, $order->client ? $order->client->$input : '', null, ['class' => "form-control", 'id' => $order->id.'-'.$input,
                                            'name' => 'order['.$order->id.']['.$input.']'], false) !!}
                                        </div>
                                    </div>
                                @endforeach
                                @if(in_array('doc_type', $required_inputs))
                                    <div class="col-md-3">
                                        <div style="width: 90%;">
                                            {!! Form::panelSelect('doc_type', trans('admin_labels.doc_types'), $order->client->doc_type ?? null,
                                                ['class' => 'form-control js_input_order_places', 'name' => 'order['.$order->id.'][doc_type]',
                                                'id' => "{{ $order->id.'-doc_type' }}"], false) !!}
                                        </div>
                                    </div>
                                @endif
                                @if(in_array('doc_number', $required_inputs))
                                    <div class="col-md-2">
                                        <div style="width: 90%;">
                                            {!! Form::panelText('doc_number', $order->client->doc_number ?? null, null, ['class' => "form-control", 
                                                'id' => $order->id.'-doc_number',
                                            'name' => 'order['.$order->id.'][doc_number]'], false) !!}
                                        </div>
                                    </div>
                                @endif
                                @if(in_array('gender', $required_inputs))
                                    <div class="col-md-2">
                                        <div style="width: 90%;">
                                            {!! Form::panelSelect('gender', trans('admin_labels.genders'), $order->doc_type ?? null,
                                                ['class' => 'form-control js_input_order_places', 'name' => 'order['.$order->id.'][gender]',
                                                'id' => "{{ $order->id.'-gender' }}"], false) !!}
                                        </div>
                                    </div>
                                @endif
                                @if(in_array('country_id', $required_inputs))
                                    <div class="col-md-2">
                                        <div style="width: 90%;">
                                            {!! Form::panelSelect('country_id', trans('admin_labels.countries'), $order->client->country_id ?? null,
                                                ['class' => 'form-control js_input_order_places', 'name' => 'order['.$order->id.'][country_id]',
                                                'id' => "{{ $order->id.'-country_id' }}"], false) !!}
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-2 text-right" style="padding: 25px;">
                                    <a data-order_id="{{$order->id}}" data-url="{{route('admin.orders.save_data_order')}}" class="col-md-offset-10 mb-10 btn btn-primary save_order_places">
                                        {{trans('admin.filter.save')}}
                                    </a>
                                </div>
                                <div class="col-md-2 text-right" style="padding: 25px;">
                                    <a target="_blank" href="{{ route('admin.orders.generate_pdf_op', $orderPlace)}}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="{{trans('admin.orders.down')}}">
                                        <i class="fa fa-file-word-o"></i>
                                    </a>
                                </div>
                            </div>
                            <div style="clear: both"><hr></div>
                        @else
                        <div>
                            <div style="padding: 25px;" class="col-md-1">
                                <div>
                                    место:<h2><b>{{$orderPlace->number}}</b></H2>
                                    @if($orderPlace->is_child)<br><b>{{trans('admin.clients.children')}}</b>@endif
                                </div>
                            </div>
                            @foreach ($order->tour->route->textInputs as $input)
                                <div class="col-md-2">
                                    <div style="width: 90%;">
                                        {!! Form::panelText($input, $orderPlace->$input, null, ['class' => "form-control", 'id' => $orderPlace->id.'-'.$input,
                                        'name' => 'order_places['.$orderPlace->id.']['.$input.']'], false) !!}
                                    </div>
                                </div>
                            @endforeach
                            @if(in_array('doc_type', $required_inputs))
                                <div class="col-md-3">
                                    <div style="width: 90%;">
                                        {!! Form::panelSelect('doc_type', trans('admin_labels.doc_types'), $orderPlace->doc_type ?? null,
                                            ['class' => 'form-control js_input_order_places', 'name' => 'order_places['.$orderPlace->id.'][doc_type]',
                                            'id' => "{{ $orderPlace->id.'-doc_type' }}"], false) !!}
                                    </div>
                                </div>
                            @endif
                            @if(in_array('doc_number', $required_inputs))
                                <div class="col-md-2">
                                    <div style="width: 90%;">
                                        {!! Form::panelText('doc_number', $orderPlace->doc_number, null, ['class' => "form-control", 'id' => $orderPlace->id.'-doc_number',
                                        'name' => 'order_places['.$orderPlace->id.'][doc_number]'], false) !!}
                                    </div>
                                </div>
                            @endif
                            @if(in_array('gender', $required_inputs))
                                <div class="col-md-2">
                                    <div style="width: 90%;">
                                        {!! Form::panelSelect('gender', trans('admin_labels.genders'), $orderPlace->doc_type ?? null,
                                            ['class' => 'form-control js_input_order_places', 'name' => 'order_places['.$orderPlace->id.'][gender]',
                                            'id' => "{{ $orderPlace->id.'-gender' }}"], false) !!}
                                    </div>
                                </div>
                            @endif
                            @if(in_array('country_id', $required_inputs))
                                <div class="col-md-2">
                                    <div style="width: 90%;">
                                        {!! Form::panelSelect('country_id', trans('admin_labels.countries'), $orderPlace->country_id ?? null,
                                            ['class' => 'form-control js_input_order_places', 'name' => 'order_places['.$orderPlace->id.'][country_id]',
                                            'id' => "{{ $orderPlace->id.'-country_id' }}"], false) !!}
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-2 text-right" style="padding: 25px;">
                                <a data-order_id="{{$order->id}}" data-url="{{route('admin.orders.save_data_order_places')}}" class="col-md-offset-10 mb-10 btn btn-primary save_order_places">
                                    {{trans('admin.filter.save')}}
                                </a>
                            </div>
                            <div class="col-md-2 text-right" style="padding: 25px;">
                                <a target="_blank" href="{{ route('admin.orders.generate_pdf_op', $orderPlace)}}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="{{trans('admin.orders.down')}}">
                                    <i class="fa fa-file-word-o"></i>
                                </a>
                            </div>
                        </div>
                        <div style="clear: both"><hr></div>
                        @endif
                        
                    @endforeach
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