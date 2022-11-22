@extends('panel::layouts.main')
@section('title', trans('admin.operational_tasks.create.title'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i>{{ trans('admin.filter.back') }}</a>
@endsection
@section('main')
    {!! Form::model([],['route' => 'admin.operational_tasks.store', 'class' => "form-horizontal js_form-ajax js_form-ajax-reset", 'enctype'=>"multipart/form-data"])  !!}
    @include('admin.operational_tasks.create.form')
    {!! Form::close() !!}
@endsection
