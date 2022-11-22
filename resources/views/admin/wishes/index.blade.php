@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.title'))

@section('actions')

@endsection

@section('main')
    <div class="ibox">
        <div class="ibox-content">
            @include('admin.'. $entity . '.index.filter')
            <div class="hr-line-dashed"></div>
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.index.table')
            </div>
        </div>
        <div class="ibox-footer js_table-pagination">
            @include('admin.partials.pagination', ['paginator' => $wishesList])
        </div>
    </div>
@endsection
