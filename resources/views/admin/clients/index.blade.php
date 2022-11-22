@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.title'))

@section('actions')
    <a href="{{ route('admin.'. $entity . '.create') }}" class="btn btn-sm btn-primary pjax-link"><span
                class="fa fa-plus"></span> {{ trans('admin.'. $entity . '.add_button') }}</a>
@endsection

@section('main')
    <div class="ibox">
        <div class="ibox-content">
            <h2>{{ trans('admin.'. $entity . '.list') }}</h2>
            <a href="{{route('admin.clients.show.template')}}">
                <span class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top"
                    title="{{trans('admin.buses.templates.download')}}">
                    <i class="fa fa-file-excel-o"></i>
                </span>
            </a>
            <form class="js_import">
                {!! Form::file('file', ['data-url' => route('admin.clients.import')]) !!}
                <a href="#" class="label btn-primary" title="Перед тем как загружать, файл экспортируйте в формате .csv">{{ trans('admin.clients.import') }}</a>
            </form>

            <a href="{{route('admin.clients.export')}}"
               class="label btn-primary">{{ trans('admin.clients.export') }}</a>
            <div class="hr-line-dashed"></div>
            @include('admin.'. $entity . '.index.filter')
            <div class="hr-line-dashed"></div>
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.index.table')
            </div>
        </div>
        <div class="ibox-footer js_table-pagination">
            @include('admin.partials.pagination', ['paginator' => $clients])
        </div>
    </div>
@endsection