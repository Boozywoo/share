@extends('index.layouts.main')

@section('title', trans('index.messages.auth.verify_phone'))

@section('content')
    <div class="mainWidth ticketMainBlock" style="margin-bottom: 40px;">
        <p class="title"><strong>{{trans('index.messages.auth.almost_done')}}</strong></p>
        <p class="title">{{trans('index.messages.auth.code_sent')}}</p>
        {!! Form::open(['route' => 'index.auth.do-confirm', 'class' => 'js_ajax-form enterStntCodeForm']) !!}
        {!! Form::text('code', null, ['class' => 'forCode', 'placeholder' => trans('index.messages.auth.enter_code')]) !!}
        <input type="submit" class="send" value="Ok">
        {!! Form::close() !!}
    </div>
@endsection