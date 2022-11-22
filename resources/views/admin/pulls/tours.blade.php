@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.title'))

@section('main')
    <div class="ibox">
        <div class="ibox-content">
            <h2>
                {{ trans('admin.tours.list') }}
                <a href="{{ route('admin.pulls.orders') }}" class="btn btn-xs btn-success pjax-link">{{ trans('admin.orders.list') }}</a>
            </h2>
            <div class="hr-line-dashed"></div>
            @include('admin.'. $entity . '.tours.filter')
            <div class="hr-line-dashed"></div>
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.tours.table')
            </div>
        </div>
        <div class="ibox-footer js_table-pagination">
            @include('admin.partials.pagination', ['paginator' => $tours])
        </div>
    </div>
@endsection