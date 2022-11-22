@extends('panel::layouts.main')
@section('title', trans('admin.'. $entity . '.single').' "'.$route->name.'" '.(empty($interval) ? '': trans('admin.routes.intervals_text')))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection

@section('main')
    <div class="ibox">
        <div class="">
            <div style="overflow-y: auto; max-height: 75%;">
                <table class="table table-responsive table-bordered table-striped table-fixed table-sm">
                    <thead style="border: none">
                    <tr>
                        <th style="border: none" class="form-horizontal form-inline">
                            <input class="form-control text-center"
                                type="text" id="all-sells"
                                style="margin-left: 35%; margin-top: 10%; min-width: 80px; max-width: 50px;"
                                data-route_id="{{$route->id}}" id="all-sells"
                                data-url="{{route('admin.routes.storeAllStationPrice')}}">
                                <a class="btn btn-default text-center js_stations_all_price" style="margin-top: 10%;">
                                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                </a>
                        </th>
                        @foreach($route->stations as $station)
                            <th style='position: sticky; top: -1px; max-width: 250px; border: none'>
                                <div style="text-align: center; padding:3%;
                                min-width: 240px; min-height: 15px">
                                    <h5 style="line-height: 0">{{mb_substr($station->city->name,0,20)}}</h5>
                                    <b style="line-height: 1.5">{{mb_substr($station->name,0,20)}}</b>
                                    <br>
                                    <a data-url="{{route('admin.routes.storeToStationPrice')}}" price="" data-station_to_id="{{$station->id}}"
                                                data-route_id="{{$route->id}}" class="js_stations_price arrow btn btn-default text-center">
                                        <span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>
                                    </a>
                                </div>
                            </th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($route->stations as $keyFrom => $stationFrom)
                        @php($filtered = $data->filter(function ($value, $key) use ($stationFrom) {
                            return $value->station_from_id == $stationFrom->id;})->keyBy('station_to_id'))
                        <tr>
                            <td style="position:sticky; left:0px; border: none; padding: 0 !important; margin: 0 !important;">
                                <div style="min-width: 250px; padding:6%; text-align: center;">
                                    <h5 style="line-height: 0">{{mb_substr($stationFrom->city->name,0,20)}}</h5>
                                    <b style="line-height: 1">{{mb_substr($stationFrom->name,0,20)}}</b>
                                    <br>
                                    <a data-url="{{route('admin.routes.storeFromStationPrice')}}" price="" data-station_from_id="{{$stationFrom->id}}"
                                                data-route_id="{{$route->id}}" class="arrow btn btn-default">
                                        <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
                                    </a>
                                </div>
                            </td>
                            @foreach($route->stations as $keyTo => $stationTo)
                                @if ($stationFrom->id != $stationTo->id && ($keyFrom < $keyTo || $route->is_route_taxi))
                                    @php($item = $filtered[$stationTo->id] ?? null)
                                    <td>
                                        <div class="prices-tbl">
                                            <input class="form-control text-center js_stations_from_to_price price-inp"
                                                type="text"
                                                data-station_from_id="{{$stationFrom->id}}"
                                                data-station_to_id="{{$stationTo->id}}"
                                                data-route_id="{{$route->id}}"
                                                data-url="{{route('admin.routes.storeStationPrice')}}"
                                                @if(empty($interval))
                                                    value="{{$item ? $item->price : null}}"
                                                    data-type="price"
                                                @else
                                                    value="{{$item ? $item->interval : ''}}"
                                                    data-type="interval"
                                                @endif

                                            >
                                        </div>
                                    </td>
                                @else
                                    <td class="pt-20">#</td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <div class="background-spinner"></div>
    <div class="js_spinner-overlay small"></div>
@endsection
<style>
    .rotate {

        /* Safari */
        -webkit-transform: rotate(-90deg);

        /* Firefox */
        -moz-transform: rotate(-90deg);

        /* IE */
        -ms-transform: rotate(-90deg);

        /* Opera */
        -o-transform: rotate(-90deg);

        float: left;

    }
</style>
<script>
    setInterval(function () {
        $("a").removeClass("pjax-link");
    } , 1000);
</script>
