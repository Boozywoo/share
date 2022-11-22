@php($isRent = (isset($tour) && $tour->rent))
@if($isRent)
    @php($is_international = true)
@else
    @php($is_international = $order->tour->route->is_international)
@endif
@php($currency = (isset($tour) && isset($tour->route) && $tour->route->currency) ? $tour->route->currency->alfa : (!empty($curr_back->alfa)?$curr_back->alfa:'BYN'))
@php($isAgent = Auth::user()->isAgent)
@php($isMediator = Auth::user()->isMediator)

<div>
    <h3 class="font-bold">{{trans('admin.buses.total')}}:</h3>
    {{trans('admin.clients.tickets')}} <span class="label label-success">{{ $order->count_places }}</span>
    &nbsp;&nbsp;&nbsp;{{trans('admin.users.total_sum')}} <span id="js_span_amount_price" class="label label-success">
        {{ number_format($order->totalPrice, env('ROUND_ORDER', 1), ',', ' ')}}
    </span>&nbsp;
    <span class="">
        {{ trans('admin_labels.currencies_short.'.$currency) }}
    </span>
</div><br />
<div class="js_div_order_places" data-url="{{route('admin.orders.save_data_order_places')}}"
    id="order_places-{{$order->id}}">
    @foreach($order->orderPlaces as $key => $place)
        @if($loop->first && !$isRent && $order->tour->route->discount_return_ticket)
            @php($discountReturnTicket = $order->tour->route->discount_return_ticket)
        @endif
        <div class="panel panel-primary">
            <div class="panel-heading">
                {{ trans('admin_labels.ticket') }}: {{ $key + 1 }}
                @if ($place->is_child) ({{trans('admin.clients.children')}}) @endif
                @if($place->is_return_ticket)
                    [{{trans('admin_labels.discount_return_ticket')}} - {{$discountReturnTicket}} {{  $order->tour->route->discount_return_ticket_type ? '%' : '' }}]
                @endif
                @if ($place->number)
                    {{trans('admin.orders.num')}}: {{ $place->number }}
                    <br>
                @endif
                <br>
                @if(!$isRent)
                    <div @if($place->is_handler_price) style="display: none"
                         @endif id="order_place_span_{{$place->id}}">
                        {{trans('admin.clients.final_price')}}:
                        <span class="label label-success">
                        @if($isMediator)
                            {{ number_format($place->price + (isset($userRoutes[$tour->route->id]) ? $userRoutes[$tour->route->id]->pivot->added_price : 0), env('ROUND_ORDER',1), ',', ' ')}}
                        @else
                            {{ number_format($place->price, env('ROUND_ORDER',1), ',', ' ')}}
                        @endif
                    </span>&nbsp;
                        <span class="">
                        {{ trans('admin_labels.currencies_short.'.$currency) }}
                    </span>
                    </div>
                @endif
                <p class="error-block"></p>
            </div>
            <div class="panel-body">
                @if(((!$isAgent && !$isMediator) || $setting->is_change_price_agent) && !$isRent)
                    <div class="row">
                        <div class="col-md-3">
                            {{trans('admin.clients.edit_price')}}:
                        </div>
                        <div class="col-md-1">
                            {!! Form::hidden("order_places[$place->id][is_handler_price]", false) !!}
                            <div class="onoffswitch">
                                <input class="onoffswitch-checkbox js_input_order_places"
                                       id="is_handler_price_{{$place->id}}" data-place_id="{{$place->id}}"
                                       @if($place->is_handler_price) checked="checked" @endif
                                       name="order_places[{{$place->id}}][is_handler_price]" type="checkbox" value="1">
                                <label for="is_handler_price_{{$place->id}}"
                                       id="js_order_places_label_price_{{$place->id}}"
                                       class="onoffswitch-label">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input @if(!$place->is_handler_price) style="display: none;"
                                   @endif  class="js_input_order_places js_price_order_places form-control"
                                   id="order_place_price_{{$place->id}}" name="order_places[{{$place->id}}][price]"
                                   type="number" value="{{$place->price}}">
                        </div>
                    </div>
                @endif
                <br>
                @if($place->sales->count() && !$place->is_child)
                    <h4 class="font-bold">{{trans('admin.clients.discounts')}}</h4>
                    <table class="table">
                        <thead>
                        <tr>
                            <td>{{trans('admin_labels.name')}}</td>
                            <td>{{trans('admin.clients.price_without_discount')}}</td>
                            <td>{{trans('admin_labels.value')}}</td>
                            <td>{{trans('admin.orders.new_price')}}</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($place->sales as $sale)
                            <tr>
                                <td>{{ $sale->name }}</td>
                                <td>{{ $sale->pivot->old_price }}</td>
                                <td>
                                    <span class="font-bold">
                                        {{ $sale->value }}
                                        @if ($sale->is_percent > 0)
                                            %
                                        @else
                                            {{ trans('admin_labels.currencies_short.'.$currency) }}
                                        @endif
                                    </span>
                                </td>
                                <td>{{ $sale->pivot->new_price }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
                @if($place->status_id)
                    <h4 class="font-bold">{{trans('admin.clients.social')}}</h4>
                    <table class="table">
                        <thead>
                        <tr>
                            <td>{{trans('admin_labels.name')}}</td>
                            <td>{{trans('admin.orders.rolling_price')}}</td>
                            @if ($place->socialStatus->is_percent > 0)
                                <td>{{trans('admin_labels.percent')}}</td>

                            @else

                                <td>{{trans('admin_labels.discount')}}</td>
                            @endif
                            <td>{{trans('admin.orders.new_price')}}</td>
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
                            @if($isMediator)
                                <td>{{ $place->price + (isset($userRoutes[$tour->route->id]) ? $userRoutes[$tour->route->id]->pivot->added_price : 0) }}</td>
                            @else
                                <td>{{ $place->price }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                @endif
                @if ($order->count_places > 1 && $is_international && $key)
                    <tr>
                        @foreach ($order->tour->route->textInputs as $input)
                            <td>
                                <div class="form-group col-md-3">
                                    <label for="{{ $place->id.'-'.$input }}" class="control-label">
                                        @if($place->is_child && $input == 'passport') {{trans('admin_labels.birth_certificate')}} @else {{trans('admin_labels.'.$input)}} @endif
                                    </label>
                                    <div>
                                        <input class="form-control js_input_order_places" id="{{ $place->id.'-'.$input }}" required="required"
                                               name="order_places[{{$place->id}}][{{ $input }}]" type="text" value="{{$place->$input}}">
                                        <p class="error-block"></p>
                                    </div>
                                </div>
                            </td>
                        @endforeach
                        @if(in_array('birth_day', $required_inputs))
                            <td>
                                <div class="form-group col-md-2">
                                    <label for="{{ $place->id.'-birth_day' }}" class=" control-label">{{trans('admin_labels.birth_day')}}</label>
                                    <div>
                                        <input class="form-control js_datepicker3 js_datepicker_order_places" id="{{ $place->id.'-birth_day' }}"
                                               name="order_places[{{$place->id}}][birth_day]" type="text" value="{{empty($place->birth_day) ? null : $place->birth_day->format('d.m.Y')}}">
                                        <p class="error-block"></p>
                                    </div>
                                </div>
                            </td>
                        @endif
                        @if(in_array('doc_type', $required_inputs))
                            <td>
                                <div class="form-group col-md-3">
                                    {!! Form::panelSelect('doc_type', trans('admin_labels.doc_types'), $place->doc_type ?? null,
                                        ['class' => 'form-control js_input_order_places', 'name' => 'order_places['.$place->id.'][doc_type]',
                                        'id' => $place->id.'-doc_type'], false) !!}
                                    <p class="error-block"></p>
                                </div>
                            </td>
                        @endif
                        @if(in_array('doc_number', $required_inputs))
                            <td>
                                <div class="form-group col-md-3">
                                    <label for="{{ $place->id.'-doc_number' }}" class="control-label">{{trans('admin_labels.doc_number')}}</label>
                                    <div>
                                        <input class="form-control js_input_order_places" id="{{ $place->id.'-doc_number' }}" required="required"
                                               name="order_places[{{$place->id}}][doc_number]" type="text" value="{{$place->doc_number ?? ''}}">
                                        <p class="error-block"></p>
                                    </div>
                                </div>
                            </td>
                        @endif
                        @if(in_array('gender', $required_inputs))
                            <td>
                                <div class="form-group col-md-2">
                                    {!! Form::panelSelect('gender', trans('admin_labels.genders'), $place->gender ?? null,
                                        ['class' => "form-control js_input_order_places", 'name' => 'order_places['.$place->id.'][gender]', 'id' => $place->id.'-gender'], false) !!}
                                    <p class="error-block"></p>
                                </div>
                            </td>
                        @endif
                        @if(in_array('country_id', $required_inputs))
                            <td>
                                <div class="form-group col-md-2">
                                    {!! Form::panelSelect('country_id', trans('admin_labels.countries'), $place->country_id ?? null,
                                        ['class' => 'form-control js_input_order_places', 'name' => 'order_places['.$place->id.'][country_id]',
                                        'id' => $place->id.'-country_id'], false) !!}
                                    <p class="error-block"></p>
                                </div>
                            </td>
                        @endif
                        @if($place->is_child)
                            {!! Form::hidden('is_child', 1, ['class' => 'js_input_order_places', 'name' => 'order_places['.$place->id.'][is_child]']) !!}
                        @endif
                    </tr>
                @endif
            </div>
        </div>
    @endforeach
</div>
<script>
    $(document).ready(function()    {
        $('.js_datepicker3').datepicker({
            format: 'dd.mm.yyyy',
            autoclose: true,
            todayHighlight: true,
            language: 'ru',
            dateFormat: 'dd.mm.yyyy',
            changeMonth: true,
            changeYear: true,
            startDate: '-1200m'
        }).on('changeDate', function (ev) {
            if ($(this).hasClass('js_table-reset-no')) $(this).trigger('change');
            if ($(this).data('date')) {
                let $date = $('[name=date]');
                $date.val($('.js_datepicker3').datepicker('getFormattedDate'));
                $date.trigger('change');
            }
        });
    });
    $('.js_price_order_places').change(function () {
        let totalPoints = 0;
        $('.js_price_order_places').each(function () {
            totalPoints = parseFloat($(this).val()) + totalPoints;
        });
        $("#js_span_amount_price").html(addCommas(totalPoints));
    })

    function addCommas(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? ',' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ' ' + '$2');
        }
        return x1 + x2;
    }
</script>
