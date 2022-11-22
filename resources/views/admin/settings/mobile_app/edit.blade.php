@extends('panel::layouts.main')

@section('title', trans('admin.settings.mobile_app.title'))
@section('main')
{!! Form::model($mobileSettings, ['route' => 'admin.settings.mobile_app.store', 'class' => 'ibox form-horizontal js_panel_form-ajax js_panel_form-ajax-reset'])  !!}
    <div class="ibox-content">
        <h2>{{ trans('admin.settings.edit') }}</h2>
        <div class="hr-line-dashed"></div>
        <div class="row">
            <div class="col-md-6">
                <div class="col-md-6">
                    <b><label for="show_place_numbers" style="cursor: pointer">{{trans('admin.settings.mobile_app.show_place_numbers')}}</label></b>
                </div>
                <div class="col-md-1 checkbox">
                    {!! Form::hidden('show_place_numbers', 0) !!}
                    <input class="checkbox" @if($mobileSettings->show_place_numbers) checked
                            @endif name="show_place_numbers" type="checkbox" value="1" id="show_place_numbers">
                    <label for="show_place_numbers"></label>
                </div>
            </div>

            <div class="col-md-6">
                {{ Form::panelText('calendar_days', $mobileSettings->calendar_days) }}
            </div>

        </div>

        <div class="hr-line-dashed"></div>
        {{ Form::panelButton() }}
        {!! Form::close() !!}
    </div>


    
@endsection
