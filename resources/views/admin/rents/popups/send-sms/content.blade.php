{!! Form::model($tour, ['route' => ['admin.'. $entity . '.sendSms', $tour], 'class' => 'ibox-content form-horizontal js_form-ajax js_form-ajax-popup js_form-ajax-table js_form-current-page']) !!}
{!! Form::hidden('id', $tour->id) !!}
<button type="button" class="close" data-dismiss="modal">
    <span aria-hidden="true">&times;</span>
    <span class="sr-only">Close</span>
</button>
<h2>{{ trans('admin.'. $entity . '.send_sms') }}</h2>
<div class="hr-line-dashed"></div>
<div class="row">
    <div class="col-md-12">
        @if($orders->count())
            <div class="table-responsive">
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th class="td-actions">#</th>
                            {{--<th class="td-actions">{{ trans('admin_labels.status') }}</th>--}}
                            <th>{{ trans('admin_labels.client_id') }}</th>
                            <th>{{ trans('admin_labels.phone') }}</th>
                            <th>{{ trans('admin_labels.tickets') }}</th>
                            <th class="text-center">{{ trans('admin_labels.count') }}</th>
                            <th>{{ trans('admin_labels.city_from_id') }}</th>
                            <th>{{ trans('admin_labels.confirm') }}</th>

                        </tr>
                    </thead>
                    <tbody>
                    @foreach($orders->load('client') as $order)
                        <tr>
                            <td>
                                <div class="checkbox m-n p-t-n">
                                    {{ Form::checkbox('orders['. $order->id .']', $order->id, true, ['class' => 'js_checkbox', 'id' => 'orders['. $order->id .']']) }}
                                    {{ Form::label('orders['. $order->id .']', $order->id) }}
                                </div>
                            {{--<td>{!! trans('pretty.statuses.'. $order->status ) !!}</td>--}}
                            <td>
                                @if($order->client)
                                    <span data-toggle="tooltip"
                                          title="@phone($order->client->phone)">{{ $order->client->first_name }}</span>
                                @else
                                    Клиент еще не создан
                                @endif
                            </td>

                            <td>
                                @if($order->client)
                                    <div class="dropdown">
                                        <button class="btn btn-default text-success" style="border:0px;"
                                                data-toggle="dropdown">
                                            <i class="text-success fa fa-phone"></i> {{ $order->client->phone ? $order->client->phone : '' }}
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{ route('admin.calls.out') }}/{{$order->client->phone}}">{{trans('admin.users.call')}}</a>
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $order->orderPlaces->implode('number', ', ') }}</td>
                            <td class="text-center">{{ $order->count_places }}</td>
                            <td><b>г.</b> {{ $order->stationFrom->city->name }}
                                <b>ост.</b> {{ $order->stationFrom->name }}</td>
                            <td>
                                {!! trans('pretty.confirm.'. $order->confirm) !!}
                                @if($noAppearanceCount = $order->orderPlaces->where('appearance', '===', 0)->count())
                                    <span class="label label-danger">Неявка: {{ $noAppearanceCount }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted m-t-sm">Брони не найдены</p>
        @endif
    </div>
</div>
<div class="hr-line-dashed"></div>
{{ Form::panelButton(trans('admin.buses.rent.send')) }}
{!! Form::close() !!}

