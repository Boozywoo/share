@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.title'))

@section('actions')
    @permission('view.settings')
    <a href="{{ route('admin.'. $entity . '.create') }}" class="btn btn-sm btn-primary pjax-link"><span class="fa fa-plus"></span> {{ trans('admin.'. $entity . '.add_button') }}</a>
    <a href="{{ route('admin.users.permissions.index') }}" class="btn btn-sm btn-primary pjax-link"><span class="fa fa-plus"></span> {{ trans('admin.users.permissions.title') }}</a>
    @endpermission
@endsection

@section('main')
    <div class="ibox">
        <div class="ibox-content">
            <h2>{{ trans('admin.'. $entity . '.list') }}</h2>
           {{-- <div class="hr-line-dashed"></div>
            @include('admin.'. $entity . '.index.filter')--}}
            <div class="hr-line-dashed"></div>
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.index.table')
            </div>
        </div>
        <div class="ibox-footer js_table-pagination">
            @include('admin.partials.pagination', ['paginator' => $roles])
        </div>
    </div>
@endsection
