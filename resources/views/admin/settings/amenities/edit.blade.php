@extends('panel::layouts.main')

@section('title', $amenity->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i>{{
    trans('admin.filter.back') }}</a>
@endsection
@section('main')
    {!! Form::model($amenity, ['route' => 'admin.'. $entity . '.store', 'class' => 'ibox form-horizontal js_form-ajax js_form-ajax-reset '.$wrapperColor])  !!}
    {!! Form::hidden('id') !!}

    {!! Form::hidden('company_id', $company) !!}
    <div class="ibox-content">
        <h2>
            {{ $amenity->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}
        </h2>
        <div class="hr-line-dashed"></div>
        <div class="row">
            <div class="col-md-6">

                <div class="row">
                    <div class="col-md-12">
                        {{ Form::panelText('name') }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {{ Form::panelSelect('status', trans('admin.settings.amenities.statuses')) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {!! $amenity->getImagesView(\App\Models\Amenity::IMAGE_TYPE_IMAGE) !!}
                    </div>
                </div>
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
