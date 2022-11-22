@extends('panel::layouts.main')
@section('title', trans('admin.operational_tasks.edit.title'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i>{{ trans('admin.filter.back') }}</a>
@endsection
@section('main')
    {!! Form::model(
        $task,
        ['route' => ['admin.operational_tasks.edit', $task->id],
        'class' => "form-horizontal js_form-ajax js_form-ajax-reset", 'enctype'=>"multipart/form-data" , 'method' => 'put']
    )  !!}
    @include('admin.operational_tasks.detail.form')
    {!! Form::close() !!}
@endsection
