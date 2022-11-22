@extends('panel::layouts.main')

@section('title', trans('admin.operational_tasks.title'))

@section('actions')

@endsection

@section('main')
    <div class="ibox">
        <div class="ibox-content">
            @include('admin.operational_tasks.index.filter')
            <div class="hr-line-dashed"></div>
            <div class="js_table-wrapper">
                @include('admin.operational_tasks.index.table')
            </div>
        </div>
        <div class="ibox-footer js_table-pagination">
            @include('admin.partials.pagination', ['paginator' => $tasks])
        </div>
    </div>
@endsection
