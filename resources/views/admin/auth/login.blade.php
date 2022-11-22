@extends('panel::layouts.auth')

@section('title',  trans('admin.auth.title'))

@section('auth')
    <div class="middle-box text-center loginscreen">
        <div>
            <h2 class="text-white">{{ trans('admin.auth.input') }}</h2>
            {!! Form::open(['route' => 'admin.auth.doLogin', 'class' => 'm-t js_form-ajax js_form-ajax-redirect']) !!}
            <div class="form-group">
                {!! Form::text('email', request('email'), ['class' => "form-control", 'placeholder' => 'Email']) !!}
                <p class="text-left error-block"></p>
            </div>
            <div class="form-group">
                {!! Form::password('password', ['class' => "form-control", 'placeholder' => trans('admin.auth.pass')]) !!}
                <p class="text-left error-block"></p>
            </div>
            <button type="submit" class="btn btn-warning block full-width m-b">{{ trans('admin.auth.log_in') }}</button>
            {!! Form::close() !!}
            <p class="m-t text-white">
                <small>@include('panel::layouts.partials.footer.copyright')</small>
            </p>
        </div>
    </div>
@stop
