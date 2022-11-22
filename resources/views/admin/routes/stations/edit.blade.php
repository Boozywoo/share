@extends('panel::layouts.main')
<?php
	if ( $station->id ) {
		$title_value = trans('admin.'. $entity . '.edit');
	} elseif ( !$station->id && $station->city_id ) {
		$title_value = trans('admin.'. $entity . '.copy');
	} else {
		$title_value = trans('admin.'. $entity . '.create');
	}
?>

@section('title', $title_value)

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection

@section('main')

    {!! Form::model($station, ['route' => 'admin.'. $entity . '.store', 'class' => "ibox form-horizontal js_form-ajax js_form-ajax-reset"])  !!}
        {!! Form::hidden('id') !!}
        <div class="ibox-content">
            <h2>{{ $title_value }}</h2>
            <div class="hr-line-dashed"></div>
            <div class="row">
                <div class="col-md-6">
                    {{ Form::panelText('name') }}
                    {{ Form::panelText('name_tr') }}
                    {{ Form::panelSelect('city_id', $cities, NULL, [ 'class' => "form-control js_city_filter"]) }}
                    {{ Form::panelSelect('street_id', $streets, NULL, [ 'class' => "form-control js_street_filter", 'data-url'=> route('admin.routes.streets.json')]) }}
                    {{--@if($station->id)--}}
                    {{ Form::panelSelect('status', trans('admin.routes.stations.statuses')) }}
                    {{--@endif--}}
                    @if(env('EGIS'))
                        {{ Form::panelText('okato_id', $station->okato_id ?? '') }}
                    @endif
                </div>
                <div class="col-md-6">
                </div>
                <div class="col-md-12">

                    <div class="hr-line-dashed"></div>
                    <div>
                        <h3 class="edit">{{ trans('admin_labels.cords') }}</h3>
                        <div>Показать все остановки <input type="button" value="&#10004;" id="toggle"/>
                        </div>
                    </div>
                    
                    <div>
                        <input type="hidden" name="latitude" value="{{ $station->latitude }}">
                        <input type="hidden" name="longitude" value="{{ $station->longitude }}">
                        <div id="map" style="width: 100%; height: 400px"></div>
                        <div id="map-all" style="width: 100%; height: 400px; display:none"></div>

                    </div>
                    <p class="error-block"></p>
                </div>
            </div>
        </div>
        <div class="ibox-footer">
            {{ Form::panelButton() }}
        </div>

        <script>
            // Как только будет загружен API и готов DOM, выполняем инициализацию
            ymaps.ready(init);

            // Инициализация и уничтожение карты при нажатии на кнопку.
            function init () {
                var myMap;

                $('#toggle').bind({
                    click: function () {
                        if (!myMap) {
                            myMap = new ymaps.Map('map-all', {
                                center: [55.76, 37.64],
                                zoom: 10
                            }, {
                                searchControlProvider: 'yandex#search'
                            }),
                            objectManager = new ymaps.ObjectManager({
                                // Чтобы метки начали кластеризоваться, выставляем опцию.
                                clusterize: true,
                                // ObjectManager принимает те же опции, что и кластеризатор.
                                gridSize: 32,
                                clusterDisableClickZoom: true
                            });

                            var myGeocoder = ymaps.geocode($("#city_id option:selected").text());

                            myGeocoder.then(
                                function (res) {
                                    var firstGeoObject = res.geoObjects.get(0), coords = firstGeoObject.geometry.getCoordinates(),
                                        bounds = firstGeoObject.properties.get('boundedBy');
                                    myMap.setCenter([res.geoObjects.get(0).geometry.getCoordinates()[0], res.geoObjects.get(0).geometry.getCoordinates()[1]], 12, {
                                        checkZoomRange: true
                                    });
                                },
                            );

                            // Чтобы задать опции одиночным объектам и кластерам,
                            // обратимся к дочерним коллекциям ObjectManager.
                            objectManager.objects.options.set('preset', 'islands#greenDotIcon');
                            objectManager.clusters.options.set('preset', 'islands#greenClusterIcons');
                            myMap.geoObjects.add(objectManager);

                            let stations = '{!! $data !!}';
                            objectManager.add(JSON.stringify(stations));
                                $("#toggle").attr('value', '✘');
                                $("#map").hide();
                                $("#map-all").show();
                        } else {
                            myMap.destroy();// Деструктор карты
                            myMap = null;
                            $("#toggle").attr('value', '✔');
                            $("#map").show();
                            $("#map-all").hide();
                        }
                    }
                });
            }
        </script>
    {!! Form::close() !!}
@endsection