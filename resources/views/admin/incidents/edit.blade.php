@extends('panel::layouts.main')

@section('title', $incident->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> {{
    trans('admin.filter.back') }}</a>
@endsection

@section('main')

    {!! Form::model($incident, ['route' => 'admin.'. $entity . '.store', 'class' => "ibox form-horizontal js_form-ajax js_form-ajax-reset $wrapperColor"])  !!}
    {!! Form::hidden('id') !!}
    @if(empty($incident->id))
        {!! Form::hidden('company_id', $company) !!}
        {!! Form::hidden('user_id', $user_id) !!}
    @endif
    <div class="ibox-content">
        <h2>{{ $incident->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}</h2>
        <div class="hr-line-dashed"></div>
        <div class="row">
            <div class="col-md-6">

                {{ Form::panelText('name') }}
                {{ Form::panelTextarea('comment') }}
                @if(!empty($incident->id))
                    {{ Form::panelSelect('status', trans('admin.incidents.statuses')) }}
                @else
                    {!! Form::hidden('status', \App\Models\Incident::STATUS_OPEN) !!}
                @endif
                {{ Form::panelSelect('incident_template_id', $templates) }}
                {{ Form::panelSelect('department_id', $departments) }}
                {{ Form::panelText('date_exec', $incident->date_exec ? $incident->date_exec->format('d.m.Y') : Carbon\Carbon::now()->format('d.m.Y'), 'js_datepicker', [$incident->id ? 'disabled' : '']) }}

            </div>
            <div class="col-md-6">
                {!! $incident->getImagesView($incident::IMAGE_TYPE_IMAGE, 'images') !!}

            </div>
        </div>
    </div>
    <div class="ibox-footer">
        {{ Form::panelButton() }}
    </div>
    {!! Form::close() !!}
@endsection
