@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.single').' "'.$company->name.'"')

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection

@section('main')
    <div class="ibox">
        <div class="ibox-content">
            <h2>{{ trans('admin.orders.list') }}</h2>
            <div class="hr-line-dashed"></div>
            @include('admin.'. $entity . '.companyStatics.filter')
            <div class="hr-line-dashed"></div>
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.companyStatics.table')
            </div>
        </div>
        <div class="ibox-footer js_table-pagination">
            @include('admin.partials.pagination', ['paginator' => $orders])
        </div>
    </div>
@endsection