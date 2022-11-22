@extends('panel::layouts.main')

@section('title', trans('admin.'.$entity.'.single').' '. $position->name)

@section('main')
    <div class="ibox">
        <div class="ibox-content">
            <h2>{{ trans('admin.'. $entity . '.index.list') }}</h2>
            <div class="hr-line-dashed"></div>
            
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.index.list.table')
            </div>
        </div>
        <div class="ibox-footer js_table-pagination">
            @include('admin.partials.pagination', ['paginator' => $users])
        </div>
    </div>
@endsection
