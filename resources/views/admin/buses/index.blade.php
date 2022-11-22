@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.title'))

@section('actions')
    @permission('view.repairs')
    <a href="{{ route('admin.'. $entity . '.create') }}" class="btn btn-sm btn-primary pjax-link"><span
                class="fa fa-plus"></span> {{ trans('admin.'. $entity . '.add_button') }}</a>
    @endpermission
    @permission('view.templates')
    <a href="{{ route('admin.'. $entity . '.templates.index') }}" class="btn btn-sm btn-success pjax-link"><span
                class="fa fa-list"></span> {{ trans('admin.'. $entity . '.templates.title') }}</a>
    @endpermission
@endsection

@section('main')
    <div class="ibox">
        <div class="ibox-content">
            <h2>{{ trans('admin.'. $entity . '.list') }}</h2>
            <a href="{{route('admin.buses.show.template')}}">
                <span class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top"
                    title="{{trans('admin.buses.templates.download')}}">
                    <i class="fa fa-file-excel-o"></i>
                </span>
            </a>
{{--            <form class="js_import">--}}
{{--                {!! Form::file('file', ['data-url' => route('admin.'.$entity.'.import')]) !!}--}}
{{--                <a href="" class="label btn-primary">{{ trans('admin.'.$entity.'.import') }}</a>--}}
{{--            </form>--}}
            <form class="js_import">
                {!! Form::file('file', ['data-url' => route('admin.'.$entity.'.import-nort')]) !!}
                <a href="" class="label btn-primary">{{ trans('admin.'.$entity.'.import') }} NorTr</a>
            </form>
            <div class="hr-line-dashed"></div>
            @include('admin.'. $entity . '.index.filter')
            <div class="hr-line-dashed"></div>
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.index.table')
            </div>
        </div>
        <div class="ibox-footer js_table-pagination">
            @include('admin.partials.pagination', ['paginator' => $buses])
        </div>
    </div>
@endsection