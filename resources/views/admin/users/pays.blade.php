@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.pays'))

@section('actions')
    <a href="{{ route('admin.salary.create',$user) }}" class="btn btn-sm btn-primary pjax-link"><span
                class="fa fa-plus"></span> {{ trans('admin.salary.add_button') }}</a>
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection

@section('main')
    <div class="ibox">
        <div class="ibox-content">
            @include('admin.'. $entity . '.pays.filter')
            <div class="hr-line-dashed"></div>
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.pays.table')
            </div>
        </div>
       {{-- <div class="ibox-footer js_table-pagination">
            @include('admin.partials.pagination', ['paginator' => $users])
        </div>--}}
    </div>
@endsection