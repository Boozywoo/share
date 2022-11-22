@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.title'))

@section('main')
    <div class="ibox">
        <div class="ibox-content">
            <h2>
                {{ trans('admin.orders.list') }}
                <a href="{{ route('admin.pulls.tours') }}" class="btn btn-xs btn-success pjax-link">{{ trans('admin.tours.list') }}</a>
            </h2>
            <div class="hr-line-dashed"></div>
            @include('admin.'. $entity . '.orders.filter')
            <div class="hr-line-dashed"></div>
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.orders.table')
            </div>
        </div>
        <div class="ibox-footer js_table-pagination">
            @include('admin.partials.pagination', ['paginator' => $orders])
        </div>
    </div>
@endsection