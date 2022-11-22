@extends('panel::layouts.main')

@section('title', $coupon->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection

@section('main')

    {!! Form::model($coupon, ['route' => 'admin.'. $entity . '.store', 'class' => "ibox form-horizontal js_form-ajax js_form-ajax-reset"])  !!}
        {!! Form::hidden('id') !!}
        <div class="ibox-content">
            <h2>
                {{ $coupon->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}
            </h2>
            <div class="hr-line-dashed"></div>
            <div class="row">
                <div class="col-md-6">
                    {{ Form::panelText('name') }}
                    {{ Form::panelText('date_start', $coupon->date_start ? $coupon->date_start->format('d.m.Y') : Carbon\Carbon::now()->format('d.m.Y'), 'js_datepicker') }}
                    {{ Form::panelText('date_finish', $coupon->date_finish ? $coupon->date_finish->format('d.m.Y') : Carbon\Carbon::now()->addWeek()->format('d.m.Y'), 'js_datepicker') }}
                    {{ Form::panelText('code') }}
                    {{ Form::panelText('max_uses') }}
                    {{ Form::panelText('percent') }}

                    @if($coupon->id)
                        {{ Form::panelSelect('status', trans('admin.settings.coupons.statuses')) }}
                    @endif
                </div>
                <div class="col-md-6">
                </div>
            </div>
        </div>
        <div class="ibox-footer">
            {{ Form::panelButton() }}
        </div>
    {!! Form::close() !!}
@endsection