{{--<link href="/assets/admin/css/easy-alert.css" rel="stylesheet" type="text/css">--}}
@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.statistic'))

@section('main')
    <div class="ibox">
        <div class="ibox-content">
            @include('admin.'. $entity . '.pays.filter')
            <div class="hr-line-dashed"></div>
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.statistic.table')
            </div>
        </div>
        {{--<div class="ibox-footer js_table-pagination">
            @include('admin.partials.pagination', ['paginator' => $users])
        </div>--}}
    </div>
@endsection