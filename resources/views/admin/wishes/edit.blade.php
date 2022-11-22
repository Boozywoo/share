
@extends('panel::layouts.main')
@section('title', trans('admin.'. $entity . '.title') . ' / ' . ($wishes->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create')))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i>{{ trans('admin.filter.back') }}</a>
@endsection
@section('main')
    <div class="ibox">
        <div class="ibox-content">
            @include('admin.'. $entity . '.index.filter')
            <div class="hr-line-dashed"></div>
        </div>
    </div>
    {!! Form::model($wishes,['route' => 'admin.'. $entity . '.store', 'class' => "form-horizontal js_form-ajax js_form-ajax-reset", 'enctype'=>"multipart/form-data"])  !!}
    @include('admin.'. $entity . '.form')
    {!! Form::close() !!}
@endsection
