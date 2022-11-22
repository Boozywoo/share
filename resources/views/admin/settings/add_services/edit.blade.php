@extends('panel::layouts.main')

@section('title', $service->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection

@section('main')

    {!! Form::model($service, ['route' => 'admin.'. $entity . '.store', 'class' => "ibox form-horizontal js_form-ajax js_form-ajax-reset"])  !!}
    {!! Form::hidden('id') !!}
    <div class="ibox-content">
        <h2>
            {{ $service->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}
        </h2>
        <div class="hr-line-dashed"></div>
        <div class="row">
            <div class="col-md-6">
                {{ Form::panelText('name') }}
                <div class="form-group">
                    {!! Form::label('value', trans('admin_labels.cost'), ['class' => 'col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::text('value', $service->value, array_merge(['class' => 'form-control'])) !!}
                        <p class="error-block"></p>
                    </div>
                </div>
                @if($service->id)
                    {{ Form::panelSelect('status', trans('admin.settings.statuses.statuses')) }}
                @endif
            </div>
            <div class="col-md-6">
            </div>
        </div>
        <div class="form-group">
            <label for="routes" class="col-md-2">{{trans('admin.routes.title')}}</label>
            <div class="col-md-10">
                <select class="js_input-select2 col-md-12" name="routes[]" multiple="multiple">
                    @foreach ($routes as $id => $route)
                        <option @if (in_array($id, $statusRoutes)) selected @endif value="{{$id}}">
                            {{$route}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="ibox-footer">
        {{ Form::panelButton() }}
    </div>
    {!! Form::close() !!}
@endsection