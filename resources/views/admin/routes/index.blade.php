@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.title'))

@section('actions')
    @permission('view.cities')
        <a href="{{ route('admin.'. $entity . '.cities.index', ['status' => 'active']) }}" class="btn btn-sm btn-warning pjax-link"><span class="fa fa-map-marker"></span> {{ trans('admin.'. $entity . '.cities.title') }}</a>
        <a href="{{ route('admin.'. $entity . '.streets.index', ['status' => 'active']) }}" class="btn btn-sm btn-warning pjax-link"><span class="fa fa-map-marker"></span> {{ trans('admin.'. $entity . '.streets.title') }}</a>
        <a href="{{ route('admin.'. $entity . '.stations.index', ['status' => 'active']) }}" class="btn btn-sm btn-warning pjax-link"><span class="fa fa-map-marker"></span> {{ trans('admin.'. $entity . '.stations.title') }}</a>
    @endpermission
    <br>
        <a href="{{ route('admin.'. $entity . '.create') }}" class="btn btn-sm btn-primary pjax-link"><span class="fa fa-plus"></span> {{ trans('admin.'. $entity . '.add_button') }}</a>
        <a href="{{ route('admin.'. $entity . '.sort') }}" class="btn btn-sm btn-primary pjax-link"><span class="fa fa-list"></span> {{ trans('admin.'. $entity . '.sort') }}</a>
@endsection

@section('main')
    <div class="ibox">
        <div class="ibox-content">
            <h2>{{ trans('admin.'. $entity . '.list') }}</h2>
            <a href="{{route('admin.routes.show.template')}}">
                <span class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top"
                    title="{{trans('admin.buses.templates.download')}}">
                    <i class="fa fa-file-excel-o"></i>
                </span>
            </a>
            <form class="js_import">
                {!! Form::file('file', ['data-url' => route('admin.'.$entity.'.import')]) !!}
                <a href="" class="label btn-primary">{{ trans('admin.'.$entity.'.import') }}</a>
            </form>
            @if(env('EGIS'))
                <form class="js_egis_send fileinput js_form-ajax">
                    <a href="#" data-url="{{ route('admin.'.$entity.'.egis_send') }}" class="label btn-primary">Отправить в ЕГИС остановки и расписания</a>
                </form>
                <form class="js_egis_status fileinput">
                    <a href="#" data-url="{{ route('admin.'.$entity.'.egis_status') }}" class="label btn-primary">Статус ЕГИС</a>
                </form>
            @endif
            <div class="hr-line-dashed"></div>
            @include('admin.'. $entity . '.index.filter')
            <div class="hr-line-dashed"></div>
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.index.table')
            </div>
        </div>
        <div class="ibox-footer js_table-pagination">
            @include('admin.partials.pagination', ['paginator' => $routes])
        </div>
    </div>
@endsection