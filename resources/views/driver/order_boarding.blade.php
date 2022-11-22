<table class="table table-bordered">
    <tr>
        <td class="text-center">
            <input type="button" class="btn btn-sm btn-success ml-auto m-2 bd-highlight icon on-station rounded-circle big-font app {{$station->id}}"
                   style="font-size: 2.5rem !important;" value="{{$station->getClientsCountFrom($orders, true)}}"
                   onclick="switchAppearanceOnStation([@foreach($station->getClients($orders) as $o) {{$o->id}}, @endforeach], {{ $tour->id }}, false, {{$station->id}})"><br>явка
        </td>
        <td>
            {{$station->getStationTime($orders)}}
            @if($d_a_setting->is_display_utc)
                {{ $station->city->getShortTimezoneAttribute()}}
            @endif
            @if($d_a_setting->is_see_map == 1 && $tour->route->is_transfer && $tour->route->flight_type == 'departure')
                <a href="#" class="btn btn-sm btn-primary pjax-link js_address_show" data-order="{{$orders->where('station_from_id', $station->id)->first()->id}}" data-toggle="tooltip" title="{{trans('admin.tours.map')}}">
                    &nbsp;<i class="material-icons" style="font-size: 16px;">&#xe55f;</i>
                </a>
            @endif
            <br>
            @if($d_a_setting->is_display_stations)
                {{$station->name}}
            @endif
            @if($d_a_setting->is_display_streets)
                {{$station->street->name ?? ''}}
            @endif
            @if($d_a_setting->is_display_cities)
                {{$station->city->name}}
            @endif
        </td>
        <td style="width: 45px;">
            <input type="button" class="btn btn-sm btn-danger ml-auto m-2 bd-highlight icon on-station rounded-circle big-font text-center noapp {{$station->id}}"
                   style="font-size: 2.5rem !important;" onclick="switchAppearanceOnStation([@foreach($station->getClients($orders) as $o) {{$o->id}}, @endforeach],
            {{ $tour->id }}, true, {{$station->id}})" value="{{$station->getClientsCountFrom($orders, false)}}">неявка
        </td>
    </tr>
    <tr>
        <td colspan="3">
            @foreach($station->getClients($orders) as $order)
                @if(!empty($order) && in_array($order->slug, $station->getClientsCountFrom($orders)))
                    <div class="orders" is_finished="{{$order->is_finished}}">
                        <p>
                            <span @if($setting->is_pay_on && $order->type_pay == 'success') style="color:lightgreen"> $ @else
                                    @if($order->is_pay) style="color:lightgreen" @else style="color:yellow" @endif>{{ $order->price}} {{ trans('admin_labels.currencies_short.'.$currency) }}@endif</span>  -
                            @if($d_a_setting->is_display_first_name == 1)
                                {{empty($order->client->first_name) ? ' ' : $order->client->first_name}}
                            @endif
                            @if($d_a_setting->is_display_middle_name == 1)
                                {{empty($order->client->middle_name) ? ' ' : $order->client->middle_name}}
                            @endif
                            @if($d_a_setting->is_display_last_name == 1)
                                {{empty($order->client->last_name) ? ' ' : $order->client->last_name}}
                            @endif
                            @if($d_a_setting->is_see_passeger_passport == 1)
                                ({{(empty($order->client->passport)) ? substr($order->client->doc_number, -$d_a_setting->count_of_passport_digits) : substr($order->client->passport, -$d_a_setting->count_of_passport_digits)}})
                            @endif
                            ({{$order->orderPlaces->count()}} чел.
                            @if (!empty($order->flight_number ))
                                рейс {{ $order->flight_number }}
                            @endif
                            )
                            <span class="glyphicon glyphicon-chevron-down btn-outline-success js_show_information" id="{{$order->id}}"></span>&nbsp;&nbsp;&nbsp;
                            <button class="btn btn-sm btn-success small_icon rounded-circle" onclick="orderSetPresence({{$order->id}}, {{$tour->id}})" title="Явка всей брони">
                               {{ $order->orderPlaces->count() }}
                            </button>
                            @if($setting->ticket_type == 5)
                                <a href="{{route ('driver.generate_pdf', [$tour, 'order' => $order])}}" class="generate">
                                    <span class="glyphicon glyphicon-download btn-outline-success" id="{{$order->id}}"></span>
                                </a>
                            @endif
                            @if(!empty($order->comment))
                                <br>
                                <span style="color: #ffbebe">Комментарий: {{$order->comment}}</span>
                            @endif
                            @foreach ($order->addServices as $item)
                                <br>{{ $item->name }}, кол-во: {{ $item->pivot->quantity }}
                                @if($order->type_pay !== \App\Models\Order::TYPE_PAY_SUCCESS)за {{ $item->pivot->quantity*$item->value.' '.__('admin_labels.currencies_short.'.$order->tour->route->currency->alfa, []) }}@endif
                            @endforeach
                            <div style="display: none;" class="{{$order->id}} row m-0">
                                <div class="col-10 p-0">
                                    @foreach($order->orderPlaces->where('type', '!=', 'completed') as $op)
                                        <div class="card" id="app_on_place{{$op->id}}">
    
                                            <div 
                                            @if($op->appearance)
                                                class="d-flex flex-nowrap bd-highlight bg-app">
                                            @elseif($op->appearance === null)
                                                class="d-flex flex-nowrap bd-highlight">
                                            @else
                                                class="d-flex flex-nowrap bd-highlight bg-no-app">
                                            @endif
                                            >
                                                <button id="{{ $op->id }}" onclick="switchAppearance({{ $op->id }}, {{ $tour->id }}, {{$op->order->id}}, {{$station->id}})"
                                                    @if($op->appearance === null) class="btn btn-sm btn-info icon m-2 btn-lg" appearance="0"><span class="glyphicon glyphicon-question-sign"></span>
                                                    @elseif($op->appearance == 0) class="btn btn-sm btn-danger icon m-2 btn-lg" appearance="0"><span class="glyphicon glyphicon-remove"></span>
                                                    @else class="btn btn-sm btn-primary icon m-2" appearance="1"><span class="glyphicon glyphicon-ok"></span>
                                                    @endif
                                                </button>
            
                                                <div class="m-2">
                                                    <b>
                                                        @if($d_a_setting->is_display_first_name == 1)
                                                            {{empty($order->client->first_name) ? ' ' : $order->client->first_name}}
                                                        @endif
                                                        @if($d_a_setting->is_display_middle_name == 1)
                                                            {{empty($order->client->middle_name) ? ' ' : $order->client->middle_name}}
                                                        @endif
                                                        @if($d_a_setting->is_display_last_name == 1)
                                                            {{empty($order->client->last_name) ? ' ' : $order->client->last_name}}
                                                        @endif
                                                    </b>
                                                    @if($d_a_setting->is_see_passeger_passport == 1)
                                                        ({{$order->client->passport ? substr($order->client->passport, -$d_a_setting->count_of_passport_digits) : substr($order->client->doc_number, -$d_a_setting->count_of_passport_digits)}})
                                                    @endif
                                                    <br>
                                                    @if($d_a_setting->is_see_passeger_phone == 1)
                                                        <u><a color="blue" href="tel:+{{empty($order->client->phone) ? ' ' : $order->client->phone}}"><b>+{{empty($order->client->phone) ? ' ' : $order->client->phone}}</b></a></u>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-1 align-self-center">
                                    <button class="btn btn-sm btn-success ml-auto m-2 bd-highlight icon on-station rounded-circle big-font" style="font-size: 2.5rem !important;"
                                            onclick="fillOrder({{$order->id}}, {{$tour->id}})">
                                        {{$order->countApp()}}
                                    </button>
                                </div>
                            </div>
                        </p>
                    </div>
                @endif
            @endforeach
        </td>
    </tr>
</table>