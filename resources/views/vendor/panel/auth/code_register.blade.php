@section('title', trans('admin.auth.registration-title'))

<div class="js-register">
    <div class="title text-black-50">{{ trans('admin.auth.registration-title') }}</div>
    {!! Form::open(['route' => 'admin.auth.code_registration', 'class' => 'm-t js_form-ajax js_form-ajax-register']) !!}
    <div class="form-group">
        {!! Form::text('register_code',null,['class' => 'form-control', 'placeholder' => trans('admin_labels.code_sms')]) !!}
        <p class="text-left error-block"></p>
    </div>
    <input hidden name="user" value="{{$id}}">
    <button type="submit" class="btn btn-warning block full-width m-b">{{ trans('admin.auth.apply') }}</button>
    {!! Form::close() !!}

    <div class="registrationscreen__question">
        {{trans('admin.auth.have-account')}}

        <a class="registrationscreen__registration-link js_form-ajax-back pjax-link" href="/admin/auth/login">
            {{trans('admin.auth.log_in')}}
        </a>
    </div>

    <style>.modal,.modal-backdrop.in{display:none!important;}</style>
</div>