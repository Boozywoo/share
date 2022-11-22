@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.title'))

@section('actions')
    <a href="{{ isset($addTariffUrl) ? $addTariffUrl : route('admin.'. $entity . '.create') }}"
       class="btn btn-sm btn-primary pjax-link"><span
                class="fa fa-plus"></span> {{ trans('admin.'. $entity . '.add_button') }}</a>
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection

@section('main')
    <div class="ibox">
            <div class="ibox-content">
            {{--<h2>{{ trans('admin.'. $entity . '.list') }} </h2>--}}
            <h2>{{ trans('admin.tariffs.name') }}: <b>{{$tariff->name}}</b></h2>
            <h2>{{ trans('admin.tariffs.type') }}: <b>{{trans('admin.tariffs.types')[$tariff->type]}}</b></h2>

            {{--<div class="hr-line-dashed"></div>
            @include('admin.'. $entity . '.index.filter')--}}
            <div class="hr-line-dashed"></div>
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.index.table')
            </div>
        </div>
        <div class="ibox-footer js_table-pagination">
            @include('admin.partials.pagination', ['paginator' => $rates])
        </div>
    </div>
@endsection