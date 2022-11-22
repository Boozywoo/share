@extends('panel::layouts.main')
@php($isAgent = Auth::user()->isAgent)
@php($isMediator = Auth::user()->isMediator)

@section('title', trans('admin.'. $entity . '.show'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection
@php($currency = (isset($tour->route) && $tour->route->currency) ? $tour->route->currency->alfa : 'BYN')
@section('main')
    <div class="ibox">
        <div class="ibox-content">
            <h2>{{trans('admin.rents.info')}}
                @if(!$isAgent && !$isMediator)
                    <span data-url="{{route ('admin.' . $entity . '.showPopup', $tour)}}" data-toggle="modal"
                            data-target="#popup_tour-edit" title="Редактировать рейс"
                            class="btn btn-sm btn-primary">
                            <i class="fa fa-edit"></i>
                    </span>
                    <span id="print_doc" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top"
                          title="Печать документа">
                            <i class="fa fa-file"> </i> <i class="fa fa-print"></i>
                    </span>
                    <span id="print_page" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top"
                            title="Печать отсортированные брони по времени посадки">
                            <i class="fa fa-arrow-right"> </i> <i class="fa fa-print"></i>
                    </span>
                    <a href="{{route('admin.tours.show.excel', $tour)}}">
                        <span class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top"
                            title="Скачать отсортированные брони по времени посадки">
                            <i class="fa fa-arrow-right"> </i>
                            <i class="fa fa-file-excel-o"></i>
                        </span>
                    </a>
                    <span id="print_page_reverse" class="btn btn-sm btn-primary" data-toggle="tooltip"
                            data-placement="top"
                            title="Печать отсортированные брони по времени высадки">
                        <i class="fa fa-print"></i> <i class="fa fa-arrow-right"></i>
                    </span>
                    <a href="{{route('admin.tours.show.reverse.excel', $tour)}}">
                        <span class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top"
                            title="Скачать отсортированные брони по времени высадки">
                            <i class="fa fa-file-excel-o"></i>
                            <i class="fa fa-arrow-right"> </i>
                        </span>
                    </a>
                    <form class="js_import">
                        {!! Form::file('file', ['data-url' => route('admin.tours.import',$tour)]) !!}

                        <a href=""> <span class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="{{trans('admin.tours.import_file')}}">
                            <i class="fa fa-file-excel-o"></i>
                            <i class="fa fa-arrow-left"> </i>
                        </span></a>
                    </form>
                    <a href="{{route('admin.tours.show.template', $tour)}}">
                        <span class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="{{trans('admin.tours.template')}}">
                            <i class="fa fa-file-excel-o"></i>
                        </span>
                    </a>
                    @if(env('FRAGMENTATION_RESERVED'))
                        <span data-url="{{route ('admin.tours.show.information', $tour)}}" data-toggle="modal"
                                data-target="#popup_tour-edit" class="btn btn-sm btn-info">{{trans('admin.tours.information')}}
                        </span>
                    @endif
                    @if (env('YANDEX_MAPS_API_KEY'))
                        <a href="{{ route('admin.tours.build_route', $tour) }}" target="_blank">
                            <span class="btn btn-sm btn-primary">
                                {{trans('admin.tours.'.($tour->mvrp_id ? 'rebuild' : 'build').'_route')}}
                            </span>
                        </a>
                    @endif
                    @if ($tour->mvrp_id)
                        <a href="https://yandex.ru/courier/mvrp-map#{{ $tour->mvrp_id }}?route=0" target="_blank">
                            <span class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top">
                                {{trans('admin.tours.show_route')}}
                            </span>
                        </a>
                    @endif
                    @if ($tour->route && $tour->route->is_transfer)
                        <a href="{{ App\Services\Geo\GeoService::getTourPointsLink($tour) }}" target="_blank">
                            <span class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top">
                                {{trans('admin.tours.map')}}
                            </span>
                        </a>
                    @endif
                    <a href="{{route('admin.tours.index')}}?date={{ $tour->date_start->format('d.m.Y') }}&tour_id={{ $tour->id }}">
                        <span class="btn btn-sm btn-primary">Показать в рейсах</span>
                    </a>
                @endif
            </h2>
            <a href="{{ request()->url() }}" class="hidden js_current-page pjax-link"></a>
            <div>
                @if ($tour->route_id)
                    <b>{{trans('admin.orders.route')}}</b> {{ $tour->route->name }}<br>
                @endif

                <b>{{trans('admin.tours.tour')}} {!! trans('pretty.statuses.'. $tour->status ) !!}</b> {{ $tour->prettyTime }}
                <br>
                <b>{{trans('admin.buses.bus')}}</b> {{ $tour->bus ? $tour->bus->number : 'Не назначен' }}<br>
                @if($tour->bus)
                    <b>{{trans('admin.drivers.driver')}}</b> {{ $tour->driver ? $tour->driver->name : '' }}<br>
                @endif
                @if($tour->comment)
                    <b>{{trans('admin.rents.comment')}}</b> {{ $tour->comment }}<br>
                @endif
                <b>{{trans('admin.users.sum')}}</b> {{ $tour->ordersReady->sum('price') }} {{ trans('admin_labels.currencies_short.'.$currency) }}
                <br>
                <b>{{trans('admin.buses.rent.quantity')}}</b> {{ $tour->ordersReady->sum('count_places') }}<br>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.show.content')
            </div>
        </div>
    </div>
@endsection
