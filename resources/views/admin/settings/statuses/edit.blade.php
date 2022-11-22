@extends('panel::layouts.main')

@section('title', $status->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection

@section('main')

    {!! Form::model($status, ['route' => 'admin.'. $entity . '.store', 'class' => "ibox form-horizontal js_form-ajax js_form-ajax-reset"])  !!}
        {!! Form::hidden('id') !!}
        <div class="ibox-content">
            <h2>
                {{ $status->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}
            </h2>
            <div class="hr-line-dashed"></div>
            <div class="row">
                <div class="col-md-6">
                    {{ Form::panelText('name') }}
                    {{ Form::panelText('percent') }}
                    {{ Form::panelText('value') }}
                    {{ Form::panelSelect('is_percent', trans('admin_labels.no_yes')) }}
                    {{ Form::panelSelect('apply_to_all_orders', __('admin_labels.no_yes')) }}
                    @if($status->id)
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
                            {{-- DONTWORK --}}
                                {{$route}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-offset-2 col-md-6">
                    {!! $status->getImagesView($status::IMAGE_TYPE_IMAGE) !!}
                </div>
            </div>
        </div>
        <div class="ibox-footer">
            {{ Form::panelButton() }}
        </div>
    {!! Form::close() !!}
@endsection