@extends('panel::layouts.main')

@section('title', $repair->id ? trans('admin.'. $entity . '.complete') .' №'.$repair->id : '')

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> {{
    trans('admin.filter.back') }}</a>
@endsection

@section('main')

    <div class="ibox form-horizontal {{ $wrapperColor }}">
        <div class="ibox-content">
            <div class="row ">
                {!! Form::model($repair, ['route' => ['admin.'. $entity . '.finish', $repair->id],'method' => 'post', 'class' => " form-horizontal js_form-ajax js_form-ajax-reset"])  !!}

                <div class="col-md-3">
                </div>

                <div class="col-md-6">
                    {!! Form::hidden('status', \App\Models\Repair::STATUS_WITHOUT_REPAIR) !!}
                    @include('admin.repair_orders.index.select-department-disabled')
                    {{ Form::panelText('name',($repair ? $repair->name : null), '',['disabled' => 'disabled']) }}
                    {{ Form::panelTextarea('comment',null, null, trans('admin_labels.comment'), ['disabled' => 'disabled']) }}
                    {{ Form::panelSelect('type', trans('admin.buses.repairs.types'), ($repair ? $repair->name : null), ['disabled' => 'disabled']) }}

                    <div class="text-center">
                        <button type="submit" class="btn btn-sm btn-primary">
                            {{ trans('admin.repair_orders.buttons.close_repair') }} </button>
                        <a class="btn btn-sm btn-default" href="{{route('admin.'.$entity.'.show',$repair->id)}}">
                            {{  trans('admin.filter.cancel') }} </a>
                    </div>
                </div>
                <div class="col-md-3">
                </div>

                {!! Form::close() !!}

            </div>
        </div>
        <div class="ibox-footer">
        </div>
    </div>

@endsection
