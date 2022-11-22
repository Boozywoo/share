{!! Form::model($tour, ['route' => ['admin.'. $entity . '.sendEgis', $tour], 'id' => 'egis-form', 'class' => 'ibox-content form-horizontal js_form-ajax js_form-ajax-popup js_form-ajax-table js_form-current-page', 'style' => 'width: 100%']) !!}
{!! Form::hidden('id', $tour->id) !!}
<button type="button" class="close" data-dismiss="modal">
    <span aria-hidden="true">&times;</span>
    <span class="sr-only">Close</span>
</button>
<h2>Отправить данные в ЕГИС</h2>
<div class="hr-line-dashed"></div>
<div class="row">
    <div class="col-md-12">
        @if($orders->count())
            <div class="table-responsive">
                <table class="table table-condensed">
                    <thead>
                    <tr>
                        <th class="td-actions">#</th>
                        <th>{{ trans('admin_labels.last_name') }}</th>
                        <th>{{ trans('admin_labels.first_name') }}</th>
                        <th>{{ trans('admin_labels.patronymic') }}</th>
                        <th>{{ trans('admin_labels.birth_day') }}</th>
                        <th>Тип документа</th>
                        <th>Номер паспорта (документа)</th>
                        <th>{{ trans('admin_labels.city_from_id') }}</th>
                        <th>{{ trans('admin_labels.city_to_id') }}</th>
                        <th>{{ trans('admin_labels.date_start_time') }}</th>
                        <th>{{ trans('admin_labels.country_id') }}</th>
                        <th>{{ trans('admin_labels.gender') }}</th>
                        <th>{{ trans('admin_labels.place') }}</th>
                        <th>{{ trans('admin_labels.date_finish_time') }}</th>
                        <th>Время покупки</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($places as $place)
                        <tr>
                            <td>
                                <div class="checkbox m-n p-t-n">
                                    {{ Form::checkbox('places['. $place->id .']', $place->id, true, ['class' => 'js_checkbox', 'id' => 'orders['. $place->id .']']) }}
                                    {{ Form::label('places['. $place->id .']', $place->id) }}
                                </div>
                            </td>

                            @if ($place->order->client)
                                <td>@exists($place->last_name)</td>
                                <td>@exists($place->first_name)</td>
                                <td>@exists($place->middle_name)</td>
                                <td>@exists($place->birth_day ? $place->birth_day->format('Y-m-d') : null)</td>
                            @else
                                <td colspan="4" class="text-danger">Клиент еще не создан</td>
                            @endif

                            <td>@exists(isset($place->doc_type) ? trans('admin_labels.doc_types.'.$place->doc_type) : null)</td>
                            <td>@exists($place->doc_number ?? $place->passport ?? null)</td>
                            <td>
                                @if ($tour->rent)
                                    {{ $tour->rent->fromCity ? $tour->rent->fromCity->name : ''}}
                                @else
                                    {{ $place->order->stationFrom->city->name }}
                                @endif
                            </td>
                            <td>
                                @if ($tour->rent)
                                    {{ $tour->rent->toCity ? $tour->rent->toCity->name : ''}}
                                @else
                                    {{ $place->order->stationTo->city->name }}
                                @endif
                            </td>
                            <td>{{ $place->order->from_date_time->format('Y-m-d\TH:i:s') }}{{ $place->order->stationFrom->city->UTCOffset }}</td>
                            <td>@exists(isset($place->country_id) ? trans('admin_labels.countries.'.$place->country_id) : null)</td>
                            <td>@exists(isset($place->gender) ? trans('admin_labels.genders.'.$place->gender) : null)</td>
                            <td>{{ $place->number }}</td>
                            <td>{{ $place->order->to_date_time->format('Y-m-d\TH:i:s') }}{{ $place->order->stationTo->city->UTCOffset }}</td>
                            <td>{{ $place->order->created_at->format('c') }}</td>
                        </tr>
                    @endforeach
                    @if($tour->driver)
                        <tr><td colspan="15" class="text-center font-bold">{{ trans('admin_labels.driver_id') }}:</td></tr>
                        <tr>
                            <td></td>
                            <td>@exists($tour->driver->last_name)</td>
                            <td>@exists($tour->driver->full_name)</td>
                            <td>@exists($tour->driver->middle_name)</td>
                            <td>@exists($tour->driver->birth_day ? $tour->driver->birth_day->format('Y-m-d') : null)</td>
                            <td>@exists(isset($tour->driver->doc_type) ? trans('admin_labels.doc_types.'.$tour->driver->doc_type) : null)</td>
                            <td>@exists($tour->driver->doc_number ?? $tour->driver->passport ?? null)</td>
                            <td>{{ $tour->route->stations->first()->city->name }}</td>
                            <td>{{ $tour->route->stations->last()->city->name }}</td>
                            <td>{{ $tour->prettyDateTimeStart->format('c') }}</td>
                            <td>@exists($tour->driver->country_id ? trans('admin_labels.countries.'.$tour->driver->country_id) : null)</td>
                            <td>@exists($tour->driver->gender ? trans('admin_labels.genders.'.$tour->driver->gender) : null)</td>
                            <td></td>
                            <td>{{ Carbon\Carbon::parse($tour->prettyDateStart.' '.$tour->prettyTimeFinish)->format('c') }}</td>
                        </tr>
                    @else
                        <tr><td colspan="15" class="text-center">Необходимо назначить водителя!</td></tr>
                    @endif
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted m-t-sm">Брони не найдены</p>
        @endif
    </div>
</div>
<div class="hr-line-dashed"></div>
<button type="submit" class="btn btn-sm btn-primary" id="js-send"> <i class="fa fa-dot-circle-o"></i> {{ trans('admin.buses.rent.send') }}</button>
&nbsp;<span id="js-egis-msg" class="font-bold @if($tour->egis_status == 'error')text-danger @endif @if($tour->egis_status == 'success')text-success @endif">{{ $tour->egis_answer ?? 'Данные в ЕГИС еще не отправлялись.'}}</span>
{!! Form::close() !!}

<script>
    startText = $('#js-egis-msg').text();
    verifyData();

        $('.js_checkbox').on('change', function () {
        if (this.checked) {
            $(this).closest('tr').find('.text-danger').addClass('js-egis-empty');
        } else {
            $(this).closest('tr').find('.js-egis-empty').removeClass('js-egis-empty');
        }
        verifyData();
    });

    function verifyData() {
        if ($('.js-egis-empty').length > 0) {
            $('#js-egis-msg').text('Необходимо заполнить данные помеченные красным цветом.');
            $('#js-send').prop('disabled', true);
        } else if($('.js_checkbox:checked').length == 0) {
            $('#js-egis-msg').text('Необходимо выбрать хотя бы одного пассажира');
            $('#js-send').prop('disabled', true);
        } else {
            $('#js-egis-msg').text(startText);
            $('#js-send').prop('disabled', false);
        }
    }

</script>