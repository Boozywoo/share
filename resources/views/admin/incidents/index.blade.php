@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.title'))


@section('actions')
    <a href="{{ route('admin.'. $entity . '.create') }}" class="btn btn-sm btn-primary pjax-link"><span class="fa fa-plus"></span> {{ trans('admin.'. $entity . '.create_btn') }}</a>
@endsection
@section('main')
    <div class="ibox {{ $wrapperColor }}">
        <div class="ibox-content">
            <h2>{{ trans('admin.'. $entity . '.list') }}</h2>
            <div class="hr-line-dashed"></div>
            {{--            @include('admin.'. $entity . '.index.filter')--}}
            <div class="hr-line-dashed"></div>
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.index.table')
            </div>
        </div>
        <div class="ibox-footer js_table-pagination">
            @include('admin.partials.pagination', ['paginator' => $incidents])
        </div>
    </div>
@endsection
