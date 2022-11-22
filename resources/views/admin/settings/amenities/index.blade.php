@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.title'))

@section('actions')
    @permission('view.settings')
    <a href="{{ route('admin.'. $entity . '.create') }}" class="btn btn-sm btn-primary pjax-link"><span class="fa fa-plus"></span> {{ trans('admin.'. $entity . '.add_button') }}</a>
    @endpermission
@endsection

@section('main')
    <div class="ibox {{$wrapperColor}}">
        <div class="ibox-content">
            <h2>{{ trans('admin.'. $entity . '.list') }}</h2>
            <div class="hr-line-dashed"></div>
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.index.table')
            </div>
        </div>
        <div class="ibox-footer js_table-pagination">
            @include('admin.partials.pagination', ['paginator' => $amenities])
        </div>
    </div>
@endsection
