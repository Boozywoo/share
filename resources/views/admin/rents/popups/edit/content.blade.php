<style>
    .selection > .select2-selection {
        border-radius: 0px !important;
        height: 35px;
    }
</style>
{!! Form::model($tour, ['route' => 'admin.'. $entity . '.store', 'class' => 'ibox-content form-horizontal js_form-ajax js_form-ajax-popup js_form-ajax-table js_form-current-page js_tours-from', 'data-wrap' => '.js_tour-edit-info', 'data-wrap-sub' => '.js_tour-edit-template']) !!}
{!! Form::hidden('id', $tour->id) !!}
{!! Form::hidden('calculation', 0) !!}
{!! Form::hidden('is_rent', 1) !!}

<button type="button" class="close" data-dismiss="modal">
    <span aria-hidden="true">&times;</span>
    <span class="sr-only">Close</span>
</button>

<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#rentEdit">
            <h2>
                {{ $tour->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}
            </h2>
        </a>
    </li>
    <li>
        <a data-toggle="tab" href="#additional">
            <h2>
                {{ trans('admin_labels.parameter') }}
            </h2>
        </a>
    </li>
</ul>

<div class="tab-content">
    <div id="rentEdit" class="tab-pane fade in active">
        <div class="hr-line-dashed"></div>
        <div class="row">
            <div class="col-md-2 js_div_agreement_info" data-url="{{route('admin.rents.getAgreementInfo')}}">
                @if ($tour->rent && ($agreement = $tour->rent->agreement))
                    @include('admin.rents.popups.edit.agreement-info')
                @endif
            </div>
            <div class="col-md-12">
                <div id="js_div_rent-tariff" data-url="{{route('admin.rents.getAgreementTariffs')}}">
                    @include('admin.rents.popups.edit.tariffs')
                </div>
                <div class="form-group">
                    {!! Form::label('user_id', 'Сотрудник', ['class' => 'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::select('user_id', $customerUsers, 1, ['class' => "form-control js-select-search-users", 'data-allow-clear'=>'true', 'data-placeholder' => 'Имя или телефон клиента']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="comment" class="col-md-4 control-label">{{ trans('admin_labels.place_address') }}</label>
                    <div class="col-md-7">
                        <div class="radio radio-warning radio-inline pt-5 js-from-station">
                            {{ Form::radio('from_type', 'from-address', true, ['id' => 'from-address']) }}<label for="from-address">Адрес</label>
                        </div>
                        <div class="radio radio-danger radio-inline pt-5 js-from-station">
                            {{ Form::radio('from_type', 'from-station', false, ['id' => 'from-station']) }}<label for="from-station">{{ trans('admin_labels.object') }}</label>
                        </div>
                        <a href="#" class="btn btn-sm btn-primary js_address_show" style="margin-left: 20px" data-toggle="tooltip" title="{{trans('admin.filter.delete')}}">{{trans('admin.tours.map')}}</a>
                    </div>
                </div>
                <div class="form-group from-inputs" id="type-from-station">
                    {!! Form::label('from_city_id', trans('admin_labels.object'), ['class' => 'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::select('from_city_id', $stations, $tour->rent ? $tour->rent->from_city_id : null, ['class' => "form-control"], $coordinates) !!}
                        <p class="error-block"></p>
                    </div>
                </div>
                <div class="form-group from-inputs" id="type-from-address">
                    <label for="comment" class="col-md-4 control-label">{{ trans('admin_labels.address') }}</label>
                    <div class="col-md-8">
                        <input class="form-control js_rent_address" type="text" name="address" id="address" autocomplete="off"
                               data-url="{{route('admin.rents.getCoordinates')}}"
                               value="{{$tour->id ? $tour->rent->address : ''}}">
                        <p class="error-block"></p>
                    </div>
                </div>
                <div id="address_map" style="width:700px; height: 400px; margin: 0px 150px 25px; display: none" class="form-group"></div>
                {{ Form::panelText('date_start', $tour->date_start ? $tour->date_start->format('d.m.Y') : Carbon\Carbon::now()->format('d.m.Y'), '', ['class' => "form-control js_datepicker"]) }}
                {{ Form::panelText('time_start', $tour->time_start ? $tour->prettyTimeStart : Carbon\Carbon::now()->format('H:i'), '', ['class' => "form-control time-mask"]) }}
                <div class="form-group">
                    <label for="comment" class="col-md-4 control-label">{{ trans('admin_labels.place_address') }}</label>
                    <div class="col-md-7">
                        <div class="radio radio-warning radio-inline pt-5 js-to-station">
                            {{ Form::radio('to_type', 'to-address', true, ['id' => 'to-address']) }}<label for="to-address">Адрес</label>
                        </div>
                        <div class="radio radio-danger radio-inline pt-5 js-to-station">
                            {{ Form::radio('to_type', 'to-station', false, ['id' => 'to-station']) }}<label for="to-station">{{ trans('admin_labels.object') }}</label>
                        </div>
                        <a href="#" class="btn btn-sm btn-primary js_address_to_show" style="margin-left: 20px" data-toggle="tooltip" title="{{trans('admin.filter.delete')}}">{{trans('admin.tours.map')}}</a>
                    </div>
                </div>
                <div class="form-group to-inputs" id="type-to-station">
                    {!! Form::label('to_city_id', trans('admin_labels.object'), ['class' => 'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::select('to_city_id', $stations, $tour->rent ? $tour->rent->to_city_id : null, ['class' => "form-control"], $coordinates) !!}
                        <p class="error-block"></p>
                    </div>
                </div>
                <div class="form-group to-inputs" id="type-to-address">
                    <label for="comment" class="col-md-4 control-label">{{ trans('admin_labels.address') }}</label>
                    <div class="col-md-8">
                        <input class="form-control js_rent_address" type="text" name="address_to" id="address_to" autocomplete="off"
                               data-url="{{route('admin.rents.getCoordinates')}}"
                               value="{{$tour->id ? $tour->rent->address_to : ''}}">
                        <p class="error-block"></p>
                    </div>
                </div>
                {{-- <div class="form-group">
                    <label for="address_to" class="col-md-4 control-label">
                        {{ trans('admin_labels.address_to') }}
                    </label>
                    <div class="col-md-7">
                        <input class="form-control js_rent_address" type="text" name="address_to" id="address_to"  autocomplete="off"
                               data-url="{{route('admin.rents.getCoordinates')}}"
                               value="{{$tour->id ? $tour->rent->address_to : ''}}">
                        <p class="error-block"></p>
                    </div>
                    <div><a href="#" class="btn btn-sm btn-primary js_address_to_show" data-toggle="tooltip" title="{{trans('admin.filter.delete')}}">Карта</a></div>
                </div>--}}
                <div id="address_to_map" style="width:700px; height: 400px; margin: 0px 150px 25px; display: none" class="form-group"></div>
                <div class="form-group">
                    <label for="comment"
                           class="col-md-4 control-label">{{ trans('admin_labels.cnt_passengers') }}</label>
                    <div class="col-md-8">
                        <input class="form-control" type="text" name="cnt_passengers"
                               value="{{$tour->id ? $tour->rent->cnt_passengers : '1'}}">
                        <p class="error-block"></p>
                    </div>
                </div>

                {{ Form::panelText('date_finish', $tour->date_finish ? $tour->date_finish->format('d.m.Y') : Carbon\Carbon::now()->format('d.m.Y'), '', ['class' => "form-control js_datepicker"]) }}
                {{ Form::panelText('time_finish', $tour->time_finish ? $tour->prettyTimeFinish : Carbon\Carbon::now()->addHour()->format('H:i'), '', ['class' => "form-control time-mask"]) }}

                {{--   {{ Form::panelSelect('bus_type_id', $busTypes,$tour->rent ? $tour->rent->bus_type_id : null) }}
                 {{ Form::panelSelect('bus_id', $buses, $tour->rent ? $tour->rent->bus_id : null, ['class' => "form-control"]) }}
                  {{ Form::panelSelect('driver_id', $drivers, null, ['class' => "form-control"]) }}  --}}
                {{ Form::panelSelect('tour_id', $tours, null, ['class' => "form-control"]) }}
            </div>
        </div>
        <div class="hr-line-dashed"></div>
    </div>
    {{--<div id="clientEdit" class="tab-pane fade">
        <div class="row">f
            <div class="hr-line-dashed"></div>
            <div class="col-md-offset-2 col-md-6">
                @php($client = empty($tour->rent->client) ? null : $tour->rent->client)
                @include('admin.rents.popups.edit.user-phone')
                <div class="js_orders-client-info">
                    @include('admin.rents.popups.edit.user-info')
                </div>
            </div>
        </div>
        <div class="hr-line-dashed"></div>
    </div>--}}
    <div id="additional" class="tab-pane fade">
        <div class="row">
            <div class="hr-line-dashed"></div>
            <div class="col-md-offset-2 col-md-6">
                <div class="form-group">
                    <label for="comment" class="col-md-4 control-label">{{ trans('admin_labels.time_wait') }}</label>
                    <div class="col-md-8">
                        <input class="form-control" type="number" name="time_wait"
                               value="{{$tour->id ? $tour->rent->time_wait : ''}}">
                        <p class="error-block"></p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="comment" class="col-md-4 control-label">{{ trans('admin_labels.add_km') }}</label>
                    <div class="col-md-8">
                        <input class="form-control" type="number" name="add_km"
                               value="{{$tour->id ? $tour->rent->add_km : ''}}">
                        <p class="error-block"></p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="bus_id" class="col-md-4">{{trans('admin_labels.is_meet_airport')}}</label>
                    <div class="col-md-8">
                        <select class="form-control" id="is_meet_airport" name="is_meet_airport">
                            <option @if($tour->rent && $tour->rent->is_meet_airport == 0) selected @endif value="0">
                                Нет
                            </option>
                            <option @if($tour->rent && $tour->rent->is_meet_airport == 1) selected @endif value="1">Да
                            </option>
                        </select>
                        <p class="error-block"></p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="comment" class="col-md-4 control-label">{{ trans('admin_labels.chair_child') }}</label>
                    <div class="col-md-8">
                        <input class="form-control" type="number" name="chair_child"
                               value="{{$tour->id ? $tour->rent->chair_child : ''}}">
                        <p class="error-block"></p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="comment" class="col-md-4 control-label">{{ trans('admin_labels.booster') }}</label>
                    <div class="col-md-8">
                        <input class="form-control" type="number" name="booster"
                               value="{{$tour->id ? $tour->rent->booster : ''}}">
                        <p class="error-block"></p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="comment" class="col-md-4 control-label">{{ trans('admin_labels.wheelchair') }}</label>
                    <div class="col-md-8">
                        <input class="form-control" type="number" name="wheelchair"
                               value="{{$tour->id ? $tour->rent->wheelchair : ''}}">
                        <p class="error-block"></p>
                    </div>
                </div>
                {{ Form::panelSelect('is_pay', Lang::get('admin_labels.no_yes'), $tour->id ? $tour->rent->is_pay : 0) }}
                {{ Form::panelSelect('type_pay', Lang::get('admin_labels.type_pays'), $tour->id ? $tour->rent->type_pay  : 0) }}
                {{ Form::panelSelect('is_legal_entity', Lang::get('admin_labels.no_yes'), $tour->id ? $tour->rent->is_legal_entity : 0) }}
            </div>
        </div>
        <div class="hr-line-dashed"></div>
    </div>
</div>
{{ Form::panelButton() }}
{!! Form::close() !!}

<script>
    $("#js_div_rent-tariff").on('change', '.js_rent-tariff', function () {
        $.get($('#js_div_rent-tariff').data('url'), {
            company_carrier_id: $('#company_carrier_id').val(),
            company_customer_id: $('#company_customer_id').val(),
            agreement_id: $('#agreement_id').val()
        }).done(function (data) {
            $('#js_div_rent-tariff').html(data);
            $.get($('.js_div_agreement_info').data('url'), {
                agreement_id: $('#agreement_id').val()
            }).done(function (data) {
                $('.js_div_agreement_info').html(data.html);
            });
        });
    });
</script>

<script>
    ymaps.ready(init_from);

    function init_from() {
        var locality = 'Россия';
        var state = 'Норильск';
        var suggestView = new ymaps.SuggestView('address', {
            provider: {
                suggest: (function(request, options) {
                    return ymaps.suggest(locality+", "+state+", "+request)
                })
            }});
        var suggestView2 = new ymaps.SuggestView('address_to', {
            provider: {
                suggest: (function(request, options) {
                    return ymaps.suggest(locality+", "+state+", "+request)
                })
            }});
        suggestView.events.add('select', function (event) {
            clickMapFrom();
        });
        suggestView2.events.add('select', function (event) {
            clickMapTo();
        });
    }
    
    $(".js-from-station input").off().on('change', function() {
        let thisId = this.id;
        $('.from-inputs').not('#type-'+thisId).slideUp(400).find('input:text,select').prop('disabled', true);      // Remove element from Post query
        $('#type-'+thisId).slideDown(400, function() {clickMapFrom();}).find('input:text,select').prop('disabled', false);
    });
    $(".js-to-station input").off().on('change', function() {
        let thisId = this.id;
        $('.to-inputs').not('#type-'+thisId).slideUp(400).find('input:text,select').prop('disabled', true);
        $('#type-'+thisId).slideDown(400, function() {clickMapTo();}).find('input:text,select').prop('disabled', false);
    });
    $("#from_city_id").off().on('change', function() {
        clickMapFrom();
    });
    $("#to_city_id").off().on('change', function() {
        clickMapTo();
    });
    
    
    $(document).ready(function () {
        @if (empty($order->custom_address_from))
            $('#from-station').trigger('click');
        @endif
        @if (empty($order->custom_address_to))
            $('#to-station').trigger('click');
        @endif

        $(".js-select-search-users").each(function () {
            var $this = $(this);
            $this.select2({
                dropdownParent: $("#popup_tour-edit .modal-content")
            }).on('select2:unselecting', function () {
                $(this).data('unselecting', true);
            }).on('select2:opening', function (e) {
                if ($(this).data('unselecting')) {
                    $(this).removeData('unselecting');
                    e.preventDefault();
                }
            });
        });
        
    });


    var addressShow = false;
    var addressToShow = false;
    
    function clickMapFrom()    {
        if (addressShow) {
            $('.js_address_show').trigger('click');
        }
    }

    function clickMapTo()    {
        if (addressToShow) {
            $('.js_address_to_show').trigger('click');
        }
    }

    $(".js_address_show").click(function () {
        addressShow = true;
        if (addressShow) {
            var addressFrom = $('#address').val();
            var zoomFrom = 16;
            if ($('#from_city_id').is(":visible")) {
                var coords = $('#from_city_id').find(':selected').data('coords').split(',');
                showMapFrom(coords[0], coords[1], zoomFrom);
            }
            if ($('#address').is(":visible") && addressFrom !== '') {
                $.get('/admin/rents/get-coordinates', {address: addressFrom}, function (response) {
                    showMapFrom(response.address[1], response.address[0], zoomFrom);
                });
            }
            $("#address_map").show();
        } else {
            $("#address_map").hide();
        }
    });

    $(".js_rent-tariff-change").change(function () {
        if (parseInt($(this).val()) === 0) {
            $("#div-rent-price").show();
        } else {
            $("#div-rent-price").hide();
        }
    })

    $(".js_address_to_show").click(function () {
        addressToShow = true;
        if (addressToShow) {
            var addressFromTo = $('#address_to').val();
            var zoomTo = 16;
            if ($('#to_city_id').is(":visible")) {
                var coords = $('#to_city_id').find(':selected').data('coords').split(',');
                showMapTo(coords[0], coords[1], zoomTo);
            }
            if ($('#address_to').is(":visible") && addressFromTo !== '') {
                $.get('/admin/rents/get-coordinates', {address: addressFromTo}, function (response) {
                    showMapTo(response.address[1], response.address[0], zoomTo);
                });
            }
            $("#address_to_map").show();
        } else {
            $("#address_to_map").hide();
        }
    });
</script>

<script>

    function showMapFrom(lat, lng, zoom) {
        var map; //Will contain map object.
        var marker = false; ////Has the user plotted their location marker?
        var centerOfMap = new google.maps.LatLng(lat, lng);

        //Map options.
        var options = {
            center: centerOfMap, //Set center.
            zoom: zoom //The zoom value.
        };

        //Create the map object.
        map = new google.maps.Map(document.getElementById('address_map'), options);

        marker = new google.maps.Marker({
            position: centerOfMap,
            map: map
        });
        /*google.maps.event.addListener(map, 'click', function (event) {
            //Get the location that the user clicked.
            var clickedLocation = event.latLng;
            //If the marker hasn't been added.
            if (marker === false) {
                //Create the marker.
                marker = new google.maps.Marker({
                    position: clickedLocation,
                    map: map,
                    draggable: true //make it draggable
                });

                //Listen for drag events!
                google.maps.event.addListener(marker, 'dragend', function (event) {
                    var currentLocation = marker.getPosition();
                    $('#address').val(currentLocation.lat() + ',' + currentLocation.lng() + '|' + currentLocation.lat() + ',' + currentLocation.lng());

                });
            } else {
                //Marker has already been added, so just change its location.
                marker.setPosition(clickedLocation);
            }
            //Get the marker's location.
            var currentLocation = marker.getPosition();
            $('#address').val(currentLocation.lat() + ',' + currentLocation.lng() + '|' + currentLocation.lat() + ',' + currentLocation.lng());

        });*/
    }

    function showMapTo(lat, lng, zoom) {
        var mapTo; //Will contain map object.
        var markerTo = false; ////Has the user plotted their location marker?
        var centerToOfMap = new google.maps.LatLng(lat, lng);

        //Map options.
        var options = {
            center: centerToOfMap, //Set center.
            zoom: zoom //The zoom value.
        };

        //Create the map object.
        mapTo = new google.maps.Map(document.getElementById('address_to_map'), options);

        marker = new google.maps.Marker({
            position: centerToOfMap,
            map: mapTo
        });
        
        /*google.maps.event.addListener(mapTo, 'click', function (event) {
            //Get the location that the user clicked.
            var clickedLocation = event.latLng;
            //If the marker hasn't been added.
            if (markerTo === false) {
                //Create the marker.
                markerTo = new google.maps.Marker({
                    position: clickedLocation,
                    map: mapTo,
                    draggable: true //make it draggable
                });
                //Listen for drag events!
                google.maps.event.addListener(markerTo, 'dragend', function (event) {
                    var currentLocation = markerTo.getPosition();
                    $('#address_to').val(currentLocation.lat() + ',' + currentLocation.lng() + '|' + currentLocation.lat() + ',' + currentLocation.lng());

                });
            } else {
                //Marker has already been added, so just change its location.
                markerTo.setPosition(clickedLocation);
            }
            //Get the marker's location.
            var currentLocation = markerTo.getPosition();
            $('#address_to').val(currentLocation.lat() + ',' + currentLocation.lng() + '|' + currentLocation.lat() + ',' + currentLocation.lng());
        });*/
    }
    
</script>


