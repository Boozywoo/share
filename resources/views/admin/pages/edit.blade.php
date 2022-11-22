@extends('panel::layouts.main')

@section('title', $page->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i class="fa fa-chevron-left"></i> {{ trans('admin.filter.back') }}</a>
@endsection

@section('main')

    {!! Form::model($page, ['route' => 'admin.pages.store', 'class' => "ibox form-horizontal js_form-ajax js_form-ajax-reset"])  !!}
    {!! Form::hidden('id') !!}
        <ul class="nav nav-tabs nav-products1" role="tablist">
            @include('admin.pages.edit.nav')
        </ul>
        <div class="ibox-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="tab-content">
                        @include('admin.pages.edit.tab-general')
                        @include('admin.pages.edit.tab-seo')
                    </div>
                </div>
            </div>
        </div>
        <div class="ibox-footer">
            {{ Form::panelButton() }}
        </div>
    {!! Form::close() !!}
@endsection