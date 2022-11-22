@extends('panel::layouts.main')

@section('title', $department->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> {{
    trans('admin.filter.back') }}</a>
@endsection

@section('main')

    {!! Form::model($department, ['route' => ['admin.'. $entity . '.store', $company], 'class' => 'ibox form-horizontal js_form-ajax js_form-ajax-reset'])  !!}
    {!! Form::hidden('id') !!}
    {!! Form::hidden('company_id', $company->id ) !!}
    <div class="ibox-content">
        <h2>{{ $department->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}</h2>
        @if(isset($department->id))
            <span data-url="{{route ('admin.' . $entity . '.set-department-popup', [$company, $department])}}" data-toggle="modal"
                  data-target="#popup_tour-edit" class="btn btn-sm btn-info">Автобусы
            </span>
        @endif
        <div class="hr-line-dashed"></div>


        <div class="row">
            <div class="col-md-6">
                {{ Form::panelText('name') }}
                
<div class="form-group">
    {{ Form::label('superdepartment_id', 'Is subdepartment of a department:', array('class' => 'col-md-4') ) }}
    <div class="col-md-8">
        {{ Form::select('superdepartment_id', $superdepartments, null, array('class' => 'form-control') ) }}
        <p class="error-block"></p>
    </div>
</div>
                
                {{ Form::panelSelect('director_id', $directors) }}
{{--                {{ Form::panelSelect('reputation', trans('admin.companies.reputations')) }}--}}
            </div>
            <div class="col-md-6">
            </div>
        </div>

    </div>
    <div class="ibox-footer">
        {{ Form::panelButton() }}
    </div>
    {!! Form::close() !!}
@endsection
