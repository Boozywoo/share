@extends('panel::layouts.main')
<style>
    .select2-container .select2-selection {
        height: 60px;
    }
    .select2-container .select2-selection--multiple {
        height: 55px !important;
    }
</style>
@section('title', $route->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection

@section('main')
    {!! Form::model($route, ['route' => 'admin.'. $entity . '.store', 'class' => "ibox form-horizontal js_form-ajax js_form-ajax-reset"])  !!}
    {!! Form::hidden('id') !!}
    <div class="ibox-content js_route-edit">
        <h2>{{ $route->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}</h2>
        @if (isset($route->id))
            <span data-url="{{route ('admin.' . $entity . '.setUserPopup', $route)}}" data-toggle="modal" data-target="#popup_tour-edit" class="btn btn-sm btn-info">
                Права доступа пользователям
            </span>
        @endif
        <div class="hr-line-dashed"></div>
        <div class="row">
            <div class="col-md-6">
                {{ Form::panelText('name') }}
                {{ Form::panelText('name_tr') }}
                @if($route->id)
                    {{ Form::panelSelect('status', trans('admin.routes.statuses')) }}
                @endif
                <div class="form-group">
                    <label for="sales" class="col-md-4">{{trans('admin.settings.sales.title')}}</label>
                    <div class="col-md-8">
                        <select class="js_input-select2 js-input-route-sales col-md-12" name="sales[]" multiple="multiple">
                            @foreach ($sales as $id => list($sale, $type))
                                <option @if (in_array($id, $salesIds, true)) selected @endif value="{{ $id }}" data-type="{{ $type }}">
                                    {{ $sale }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{ Form::panelSelect('currency_id',  $currencies) }}
                {{ Form::panelSelect('phone_code', $phoneCodes) }}
                {{ Form::panelText('mileage') }}
                {{ Form::panelText('time_hidden_tour_front') }}
            </div>
            <div class="col-md-6">
                {{ Form::panelSelect('is_international', trans('admin.routes.is_international')) }}
                {{ Form::panelSelect('is_line_price', trans('admin.routes.is_international')) }}
                {{ Form::panelSelect('type', trans('admin_labels.regular_transfer'), $route->typeName, ['class' => 'form-control js_route_type']) }}

                <div class="js_flight_type" @if (!$route->is_transfer)style="display: none"@endif>
                    {{ Form::panelSelect('flight_type', trans('admin_labels.flight_types'), $route->flight_type) }}
                </div>

                {{ Form::panelSelect('allow_ind_transfer', trans('admin.routes.is_international')) }}

                {{ Form::panelTextSelect(['text'=>['name'=>'discount_front', 'value'=>$route->discount_front],
                'select'=>['name'=>'discount_front_type', 'values'=>trans('admin.routes.discount_types'), 'selected'=>$route->discount_front_type]]) }}

                {{ Form::panelTextSelect(['text'=>['name'=>'discount_mobile', 'value'=>$route->discount_mobile],
                'select'=>['name'=>'discount_mobile_type', 'values'=>trans('admin.routes.discount_types'), 'selected'=>$route->discount_mobile_type]]) }}

                {{ Form::panelTextSelect(['text'=>['name'=>'discount_return_ticket', 'value'=>$route->discount_return_ticket],
                'select'=>['name'=>'discount_return_ticket_type', 'values'=>trans('admin.routes.discount_types'), 'selected'=>$route->discount_return_ticket_type]]) }}
                {{ Form::panelTextSelect(['text'=>['name'=>'discount_child', 'value'=>$route->discount_child],
                'select'=>['name'=>'discount_child_type', 'values'=>trans('admin.routes.discount_types'), 'selected'=>$route->discount_child_type]]) }}
                {{ Form::panelTextSelect(['text'=>['name'=>'bonus_agent', 'value'=>$route->bonus_agent],
                'select'=>['name'=>'bonus_agent_type', 'values'=>trans('admin.routes.discount_types'), 'selected'=>$route->bonus_agent_type]]) }}
                {{ Form::panelTextSelect(['text' => ['name' => 'bonus_driver', 'value' => $route->bonus_driver],
                'select' => ['name'=>'bonus_driver_type', 'values' => trans('admin.routes.discount_types'), 'selected' => $route->bonus_driver_type]]) }}

                @if (env('PARTIAL_PREPAID'))
                    {{ Form::panelTextSelect(['text' => ['name' => 'partial_prepaid', 'value' => $route->partial_prepaid],
                        'select' => ['name'=>'bonus_driver_type', 'values' => ['В процентах'], 'selected' => 0]]) }}
                @endif
                <div class="form-group">
                    <label for="required_inputs" class="col-md-4">{{trans('admin_labels.req_fields')}}</label>
                    <div class="col-md-8">
                        <select class="js_input-select2 col-md-12 height: 150%;" name="required_inputs[]" multiple="multiple">
                            @foreach ($user_fillable as $name => $item)
                                <option @if(($name == 'phone' or $name == 'first_name'))
                                        selected
                                        @endif
                                        @if (in_array($name, $route->required_inputs))) selected
                                        @endif value="{{$name}}">
                                    {{$item}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @if(env('EGIS'))
                    {{ Form::panelRadio('is_egis') }}
                @endif
                <br>
                <div class="col-md-12">

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('admin.routes.edit.stations')
            </div>
        </div>
    </div>
    <div class="ibox-footer">
        {{ Form::panelButton() }}
    </div>
    {!! Form::close() !!}
@endsection