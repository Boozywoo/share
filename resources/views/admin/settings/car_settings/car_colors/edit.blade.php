@extends('panel::layouts.main')

@section('title', $carColor->id ? trans('admin.'. $entity . '.edit_title') : trans('admin.'. $entity . '.create_title'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection

@section('main')

    @if($carColor->id)
        {!! Form::model($carColor, ['route' => ['admin.'. $entity . '.update', $carColor->id], 'method' =>'put', 'class' => "ibox form-horizontal js_form-ajax js_form-ajax-reset"])  !!}
    @else
        {!! Form::model($carColor, ['route' => 'admin.'. $entity . '.store', 'class' => "ibox form-horizontal js_form-ajax js_form-ajax-reset"])  !!}
    @endif
    {!! Form::hidden('id') !!}
    <div class="ibox-content">
        <h2>
            {{ $carColor->id ? trans('admin.'. $entity . '.edit_title') : trans('admin.'. $entity . '.create_title') }}
        </h2>
        <div class="hr-line-dashed"></div>
        <div class="row">
            <div class="col-md-6">
                {{ Form::panelText('name') }}
                {{ Form::panelText('slug') }}
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
