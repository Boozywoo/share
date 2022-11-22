@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.title'))

@section('main')
<div class="ibox">
    <div class="ibox-content">
        <h2>{{ trans('admin.'. $entity . '.list') }}</h2>
        <div class="hr-line-dashed"></div>
        <div class="js_filter-wrapper">
            @include('admin.'. $entity . '.index.filter')
        </div>
        <div class="hr-line-dashed"></div>
       <!--  <div class="js_table-wrapper">
            @include('admin.'. $entity . '.index.table')
        </div> -->
        @include('admin.'. $entity . '.index.map')
    </div>
</div>

@endsection
