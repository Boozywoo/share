@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection

@section('main')
    {!! Form::model($user, ['route' => 'admin.'. $entity . '.store', 'class' => "ibox form-horizontal js_form-ajax js_form-ajax-reset"]) !!}
    {!! Form::hidden('user_id', $user->id) !!}
    <div class="ibox-content">
        <div class="hr-line-dashed"></div>
        <div class="row">
            <div class="col-md-6">
                {{ Form::panelText('sum') }}
                {{ Form::panelSelect('currency_id',  App\Models\Currency::all('id', 'name')->pluck('name', 'id')) }}
            </div>
        </div>
    </div>
    <div class="ibox-footer">
        {{ Form::panelButton() }}
    </div>
    {!! Form::close() !!}
@endsection
