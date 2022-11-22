@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.show'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection

@section('main')
    <div class="ibox">
        <div class="ibox-content">
            <h2>{{trans('admin.rents.info')}}
                <span data-url="{{route ('admin.' . $entity . '.showPopup', $tour)}}" data-toggle="modal"
                      data-target="#popup_tour-edit"
                      class="btn btn-sm btn-primary">
                        <i class="fa fa-edit"></i>
                </span>
                <span id="print_page" class="btn btn-sm btn-primary">
                        <i class="fa fa-print"></i>
                </span>
            </h2>
            <a href="{{ request()->url() }}" class="hidden js_current-page pjax-link"></a>
            <div>
                @if ($tour->route_id)
                    <b>{{trans('admin.orders.route')}}</b> {{ $tour->route->name }}<br>
                @endif

                <b>{{trans('admin.tours.tour')}} {!! trans('pretty.statuses.'. $tour->status ) !!}</b> {!! $tour->prettyTime !!}<br>
                <b>{{trans('admin.buses.bus')}}</b> {{ $tour->bus ? $tour->bus->number : 'Не назначен' }}<br>
                @if($tour->driver)
                    <b>{{trans('admin.drivers.driver')}}</b> {{ $tour->driver->full_name}}<br>
                @endif
                @if($tour->comment)
                    <b>{{trans('admin.rents.comment')}}</b> {{ $tour->comment }}<br>
                @endif
            </div>
            <div class="hr-line-dashed"></div>
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.show.content')
            </div>
        </div>
    </div>
@endsection