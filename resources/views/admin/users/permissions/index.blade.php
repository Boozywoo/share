@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.title'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection

@section('main')
    <div class="ibox">
        <div class="ibox-content">
            <h2>{{ trans('admin.'. $entity . '.title') }}</h2>
            <div class="hr-line-dashed"></div>
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.index.table')
            </div>
        </div>
    </div>
@endsection