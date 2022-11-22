@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.title'))

@section('actions')
    <a href="{{ route('admin.routes.cities.index', ['status' => 'active']) }}" class="btn btn-sm btn-warning pjax-link"><span class="fa fa-map-marker"></span> {{ trans('admin.routes.cities.title') }}</a>
    <a href="{{ route('admin.routes.streets.index', ['status' => 'active']) }}" class="btn btn-sm btn-warning pjax-link"><span class="fa fa-map-marker"></span> {{ trans('admin.routes.streets.title') }}</a>
    <a href="{{ route('admin.routes.stations.index', ['status' => 'active']) }}" class="btn btn-sm btn-warning pjax-link"><span class="fa fa-map-marker"></span> {{ trans('admin.routes.stations.title') }}</a>
    <br>
    <a href="{{ route('admin.'. $entity . '.create') }}" class="btn btn-sm btn-primary pjax-link"><span class="fa fa-plus"></span> {{ trans('admin.'. $entity . '.add_button') }}</a>
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection

@section('main')
    <div class="ibox">
        <div class="ibox-content">
            <h2>{{ trans('admin.'. $entity . '.list') }}</h2>
            <div class="hr-line-dashed"></div>
            @include('admin.'. $entity . '.index.filter')
            <div class="hr-line-dashed"></div>
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.index.table')
            </div>
        </div>
        <div class="ibox-footer js_table-pagination">
            @include('admin.partials.pagination', ['paginator' => $stations])
        </div>
    </div>
@endsection