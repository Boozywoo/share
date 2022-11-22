@extends('panel::layouts.auth')

@section('title', trans('admin.auth.change'))

@section('auth')
    <div class="middle-box text-center loginscreen">
        <div>
            <h2 class="text-white">{{ trans('admin.auth.change') }}</h2>

            <div class="alert alert-danger" role="alert">
                <b>{{ trans('admin.auth.attention') }}</b> <br>
                {{ trans('admin.auth.security') }}
            </div>
            {!! Form::open(['route' => 'admin.auth.doChangePassword', 'class' => 'm-t js_form-ajax js_form-ajax-redirect']) !!}
            <div class="form-group">
                {!! Form::text('old_password', request('old_password'), ['class' => "form-control", 'placeholder' => trans('admin_labels.old_password')]) !!}
                <p class="text-left error-block"></p>
            </div>
            <div class="form-group">
                {!! Form::password('new_password', ['class' => "form-control", 'placeholder' => trans('admin_labels.new_password')]) !!}
                <p class="text-left error-block"></p>
            </div>
            <div class="form-group">
                {!! Form::password('new_password_confirmation', ['class' => "form-control", 'placeholder' => trans('admin.auth.repeat')]) !!}
                <p class="text-left error-block"></p>
            </div>
            <button type="submit" class="btn btn-warning block full-width m-b">{{ trans('admin.auth.apply') }}</button>
            {!! Form::close() !!}
            <p class="m-t text-white">
                <small>@include('panel::layouts.partials.footer.copyright')</small>
            </p>
        </div>
    </div>
@stop