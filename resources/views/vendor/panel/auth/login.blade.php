@extends('panel::layouts.auth')

@section('title', trans('admin.auth.title'))

@section('auth')
    <div class="middle-box loginscreen">
        <div>
            <div class="title text-black-50">{{ trans('index.messages.auth.login_to_control_panel') }}</div>
            {!! Form::open(['route' => 'admin.auth.doLogin', 'class' => 'm-t js_panel_form-ajax js_panel_form-ajax-redirect']) !!}
            <div class="form-group">
                {!! Form::text('email', request('email'), ['class' => 'form-control', 'placeholder' => 'Email']) !!}
                <p class="text-left error-block"></p>
            </div>
            <div class="form-group">
                {!! Form::password('password', ['class' => 'form-control', 'placeholder' => trans('admin.auth.pass')]) !!}
                <p class="text-left error-block"></p>
            </div>
            @if($sip_reg==1)
            <div class="form-group">
                {!! Form::text('sip', $sip_value, ['class' => 'form-control', 'placeholder' => 'Sip']) !!}
                <p class="text-left error-block"></p>
            </div>
            @endif
            <button type="submit" class="btn btn-warning block full-width m-b">{{ trans('index.messages.auth.login') }}</button>
            {!! Form::close() !!}
            <div class="loginscreen__question">{{trans('admin.auth.ne_reg_question')}}</div>
            <a class="loginscreen__registration-link" data-url="{{route ('admin.auth.popup_registration')}}"
               data-toggle="modal" data-target="#popup_tour-edit">
                {{trans('admin.auth.registration')}}
            </a>
            <p class="m-t text-white">
                <small>@include('panel::layouts.partials.footer.copyright')</small>
            </p>
        </div>
    </div>
    <div class="middle-box registrationscreen"></div>
@stop
