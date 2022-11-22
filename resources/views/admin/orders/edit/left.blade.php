@php($currency = (isset($tour) && isset($tour->route) && $tour->route->currency) ? $tour->route->currency->alfa : (!empty($curr_back->alfa)?$curr_back->alfa:'BYN'))
<div class="js_orders-filter">
    @if(isset($tour) && !$tour->id)
        @include('admin.orders.edit.left.filter')
    @endif
</div>

<style>
    td label{
        padding-top: 5px;
    }
    .selection > .select2-selection {
        border-radius: 0px !important;
        height: 35px;
    }
</style>

{!! Form::open(['route' => 'admin.orders.store', 'class' => "ibox js_orders-from js_form-ajax"]) !!}
<div class="ibox-content">
    {!! Form::hidden('id', $order->id, ['class' => 'js_orders-id']) !!}
    {!! Form::hidden('order_slug', $order->slug, ['class' => "js_orders-slug"]) !!}
    {!! Form::hidden('tour_id', isset($tour) && $tour->id ? $tour->id : $order->tour_id, ['class' => 'js_orders-tour_id']) !!}
    {!! Form::hidden('confirm', $order->id ? $order->confirm : 1) !!}
    {!! Form::hidden('places_with_number', $order->id ? $order->places_with_number : $tour->reservation_by_place, ['class' => 'js_orders-places_with_number']) !!}
    {{--{!! Form::hidden('type', !$order->id ? \App\Models\Order::TYPE_WAITING : ($order->type == \App\Models\Order::TYPE_WAITING ? \App\Models\Order::TYPE_EDITED : $order->type), ['class' => 'js_orders-type']) !!}--}}
    {!! Form::hidden('type', !$order->id ? \App\Models\Order::TYPE_NO_COMPLETED : ($order->type == \App\Models\Order::TYPE_WAITING ? \App\Models\Order::TYPE_EDITED : $order->type), ['class' => 'js_orders-type']) !!}
    {!! Form::hidden('order_return', request('order_return') ?? '', ['id' => 'js_order_return']) !!}

    @if(request()->get('status') == \App\Models\Order::STATUS_RESERVE)
        {!! Form::hidden('pull', 1) !!}
    @else
        {!! Form::hidden('pull', 0) !!}
    @endif

    {!! Form::hidden('new_order', !$order->id ? 1 : 0) !!}

    <div class="js_orders-places-input">
        @if($order->orderPlaces->count())
            @foreach($order->orderPlaces as $place)
                {!! Form::hidden('places[]', $place->number, ['data-number' => $place->number]) !!}
            @endforeach
        @elseif(isset($tour) && !$tour->reservation_by_place)
            {!! Form::hidden('places[]', '', ['data-number' => '']) !!}
        @endif
    </div>
    <h3>{{trans('admin.clients.client')}}</h3>
    @if(isset($client))
        @include('admin.orders.edit.left.user-phone')
    @elseif($client = \App\Models\Client::where('phone', request()->get('phone'))->first())
        @include('admin.orders.edit.left.user-phone', ['client' => $client])
    @else
        @include('admin.orders.edit.left.user-phone', ['client' => $order->client])
    @endif
    <p>Отправить смс пассажиру  <input id='is_send_sms' name='is_send_sms' type="checkbox" 
        @if(!\App\Models\Setting::first()->auto_turn_notification 
            || (\App\Models\Setting::first()->auto_turn_notification && !($tour->is_edit))) checked @endif></p>


    <div class="js_orders-client-info">
        @php($userClient = null)
        @if(isset($client))
            @php($userClient = $client)
        @elseif($userClient = \App\Models\Client::where('phone', request()->get('phone'))->first())
        @else
            @php($userClient = $order->client)
        @endif
        @include('admin.orders.edit.left.user-info', ['client' => $userClient])
    </div>
    <div class="js_orders-tour-info">
        @if(isset($tour->id) && !$order->id && !$tour->rent_id)
            @include('admin.orders.edit.left.tour-info', ['tour' => $tour])
        @elseif($order->tour)
            @include('admin.orders.edit.left.tour-info', ['tour' => $order->tour])
        @endif
    </div>
    {!! Form::panelText('comment', $order->comment, null, ['class' => "form-control"], false) !!}
    <div class="js-flightNumber"></div>
    @if (in_array('flight_number', $required_inputs))
        {!! Form::panelText('flight_number', $order->flight_number ?? (isset($tour->schedule) ? $tour->schedule->flight_ac_code . '-' . $tour->schedule->flight_number : ''), null, ['class' => "form-control"], false) !!}
    @endif
    @php($statuses = trans('admin.orders.statuses'))
    @php(array_pop($statuses))
    @php($freePlacesCount = env('FRAGMENTATION_RESERVED') ? $tour->ordersFreeCity(request('city_from_id'), request('city_to_id')) : $tour->freePlacesCount)
    @if ($order->status == \App\Models\Order::STATUS_RESERVE && $freePlacesCount < 1) @php(array_shift($statuses)) @endif
    {!! Form::panelSelect('status', $statuses, $order->status, [], false) !!}
    <div class="div_social_status" data-url="{{route('admin.clients.get_social_status')}}">
        {!! Form::panelSelect('status_id', App\Models\Status::SelectStatuses(), $client ? $client->status_id : 0,
            [   
                'class' => "form-control  js_orders-client-status_is",
                'data-url' =>route('admin.clients.change_status'),
            ], 
            false
        ) !!}

        {{ Form::panelText('date_social', 
            (isset($client)) && $client->date_social ? $client->date_social->format('d.m.Y') : '', 'js_datepicker js_orders-client-date_social',
            [
                'class' => "form-control js_datepicker js_orders-client-date_social",
                'data-url' =>route('admin.clients.change_date_social'),
            ],
            false
        ) }}
    </div>
    @if ($tour->route && $tour->route->addServices->count())
        <div class="form-group" style="overflow: auto;">
            <label class="control-label" style="font-weight: bold">{{ trans('admin.settings.add_services.title') }}</label>
                <table class="table table-condensed">
                    <th>{{ trans('admin_labels.service') }}</th>
                    <th>{{ trans('admin_labels.cost') }}</th>
                    <th>{{ trans('admin_labels.quantity') }}</th>
                    <th>{{ trans('admin.buses.total') }}</th>
                    @foreach ($tour->route->addServices as $item)
                        <tr>
                            <td><label for="service-{{ $item->id }}">{{ $item->name }}</label></td>
                            <td>
                                <label for="service-{{ $item->id }}"><span id="cost-{{ $item->id }}">{{$item->value }}</span> {{ trans('admin_labels.currencies_short.'.$currency) }}</label>
                            </td>
                            <td><input class="form-control js-dop-number" min="0" style="width: 60%" data-id="{{ $item->id }}" id="service-{{ $item->id }}" name="add_services[{{ $item->id }}]" type="number" value="{{ $addServices[$item->id]->pivot->quantity ?? 0}}"></td>
                            <td><label><span class="js-dop-cost" id="total-{{ $item->id }}"></span></label></td>
                        </tr>
                    @endforeach
                    <td colspan="3">{{ trans('admin.settings.add_services.title') }} - {{ mb_strtolower(trans('admin_labels.total_sum')) }}</td>
                    <td><span id="add-service-total">0</span> {{ trans('admin_labels.currencies_short.'.$currency) }}</td>
                </table>
        </div>
    @endif
</div>
<div class="ibox-footer">

    <button class="btn btn-sm btn-warning js_order_calculation @if (!empty(request()->get('order_return'))) hidden @endif">{{trans('admin.orders.calc')}}</button>
    <span class="btn btn-sm btn-primary js_orders-completed" data-type="{{ \App\Models\Order::TYPE_WAITING }}"> <i
                class="fa fa-dot-circle-o"></i> {{trans('admin.filter.save')}} </span>
    @if(!$order->id)
        <a class="btn btn-sm btn-danger js_order-cancel hidden" data-id="" href="{{ route('admin.orders.delete') }}">{{trans('admin.filter.cancel')}} </a>
    @endif
</div>
<div class="ibox-footer">
    <span class="btn btn-sm btn-primary js_orders-completed_continue" data-url="{{ route('admin.tours.index') }}"
          data-type="{{ \App\Models\Order::TYPE_WAITING }}"><i class="fa fa-dot-circle-o"></i> {{trans('admin.orders.save')}}</span>
    @if (empty(request()->get('order_return')))
        <span class="btn btn-sm btn-success js_orders-completed_return" data-url="{{ route('admin.tours.index') }}"
              data-type="{{ \App\Models\Order::TYPE_WAITING }}"><i class="fa fa-dot-circle-o"></i> Сохранить и создать обратную бронь %</span>
    @endif
</div>
{!! Form::close() !!}

<script>
    $(document).ready(function()
    {
        $('.js-dop-number').on('change', function () {
            let dopId = $(this).data('id');
            if ($(this).val() > 0) {
                $('#total-'+dopId).text(parseFloat($(this).val()*$('#cost-'+dopId).text()).toFixed(2));
            } else {
                $('#total-'+dopId).text('');
            }
            let dopTotal = 0;
            $('.js-dop-cost').each(function () {
                let elem = parseFloat($(this).text())
                if (elem > 0) {
                    dopTotal += elem;
                }
            });
            $('#add-service-total').text(dopTotal.toFixed(2));
        });
        $('.js-dop-number').trigger('change');
    });
</script>

