@extends('panel::layouts.main')

@section('title', $rent->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i class="fa fa-chevron-left"></i> {{
    trans('admin.filter.back') }}</a>
@endsection

@section('main')

    {!! Form::model($rent, ['route' => 'admin.'. $entity . '.store', 'class' => "ibox form-horizontal js_form-ajax js_form-ajax-reset"])  !!}
        {!! Form::hidden('id') !!}
        {!! Form::hidden('bus_id', request('bus_id')) !!}
        <div class="ibox-content">
            <h2>{{ $rent->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}</h2>
            <div class="hr-line-dashed"></div>
            <div class="row">
                <div class="col-md-6">
                    {{ Form::panelText('from_hour', $rent->from_hour, null,['disabled' => true]) }}
                    <div class="form-group">
                        <label for="to_hour" class="col-md-4">@lang('admin_labels.to_hour')</label>
                        <div class="col-md-8">
                            <input class="form-control " type="number" min="{!! $rent->from_hour + 1 !!}" value="{{$rent->from_hour + 1}}" name="to_hour" value="{{$rent->to_hour}}" id="to_hour">
                            <p class="error-block"></p>
                        </div>
                    </div>
                    {{ Form::panelText('cost') }}
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