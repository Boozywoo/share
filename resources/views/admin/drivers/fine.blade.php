@extends('panel::layouts.main')

@section('title', $fine ? trans('admin.'. $entity . '.edit_fine') : trans('admin.'. $entity . '.add_fine'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i class="fa fa-chevron-left"></i> {{
    trans('admin.filter.back') }}</a>
@endsection

@section('main')
    {!! Form::model($fine, ['route' => 'admin.'. $entity . '.store_fine', 'class' => "ibox form-horizontal js_form-ajax js_form-ajax-reset"]) !!}
    {!! Form::hidden('driver_id', $driver->id) !!}
    @if($fine)
        {!! Form::hidden('id', $fine->id) !!}
    @endif
    <div class="ibox-content">
        <div class="hr-line-dashed"></div>
        <div class="row">
            <div class="col-md-6">
                {{ Form::panelText('sum') }}
                {{ Form::panelText('date', isset($fine) && $fine->date ? date('d.m.Y',strtotime($fine->date)) : Carbon\Carbon::now()->format('d.m.Y'),'js_datepicker') }}
                {{ Form::panelSelect('type', $types) }}
                {{ Form::panelSelect('is_pay', [trans('admin.home.no'), trans('admin.home.yes')]) }}
                <div class="form-group">
                    <label for="email" class="col-md-4">{{trans('admin.auth.desc')}}</label>
                    <div class="col-md-8">
                        <textarea class="form-control" name="description" rows="4">{{isset($fine) && $fine->description ? $fine->description : '' }}</textarea>
                        <p class="error-block"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ibox-footer">
        {{ Form::panelButton() }}
    </div>
    {!! Form::close() !!}
@endsection