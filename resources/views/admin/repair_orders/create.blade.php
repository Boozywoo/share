@extends('panel::layouts.main')

@section('title', $repair->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> {{
    trans('admin.filter.back') }}</a>
@endsection

@section('main')

    <div class="ibox form-horizontal {{ $wrapperColor }}" >
        <div class="ibox-content">
            <div class="row ">
                @if($repair->id)
                    {!! Form::model($repair, ['route' => ['admin.'. $entity . '.update', $repair->id],'method' => 'put', 'class' => " form-horizontal js_form-ajax js_form-ajax-redirect"])  !!}
                @else
                    {!! Form::model($repair, ['route' => 'admin.'. $entity . '.store', 'class' => " form-horizontal js_form-ajax js_form-ajax-redirect"])  !!}
                @endif

                {!! Form::hidden('id') !!}
                <div class="col-md-3"></div>

                <div class="col-md-6 ibox-content-area">

                    @include('admin.repair_orders.index.select-department')
                    <div class="form-group margin-disable">
                        {!! Form::text('name',($repair->name ? $repair->name : null),['class' => 'form-control ibox-content-item ibox-content-item-text','placeholder' => __('admin.repair_orders.fields.short_description'), "autocomplete"=>"off"])!!}
                        <p class="error-block"></p>
                    </div>
{{--                    <div class="form-group margin-disable">--}}
{{--                        {{ Form::textarea('comment',($repair->comment ? $repair->comment : null),['class' => 'form-control ibox-content-item','placeholder' => __('admin.repair_orders.fields.full_description')]) }}--}}
{{--                        <p class="error-block"></p>--}}
{{--                    </div>--}}

                    <div class="form-group margin-disable">
                        {!! Form::select('type', trans('admin.buses.repairs.types'), ($repair->type ? $repair->type :null), ['class' => "form-control ibox-content-item",'placeholder' => __('admin_labels.type'), 'id' => 'select_repair_type_in_create' ]) !!}

                        <p class="error-block"></p>
                    </div>
                    <div class="form-group margin-disable ">

                        <span>{{__('admin_labels.bus_status')}} : </span>
                        {!! Form::hidden('bus_status', $repair->bus && $repair->bus->status ? $repair->bus->status : '') !!}

                        @foreach($busStatuses as $key=>$status)
                            @if($key == \App\Models\Bus::STATUS_ACTIVE)
                                <div class="btn btn-primary {{($repair->bus && $repair->bus->status == $key ? ' btn-selected' : '')}}"
                                     id="status_{{$key}}" onclick="selectStatus('{{$key}}')">{{$status}}</div>
                            @endif

                            @if($key == \App\Models\Bus::STATUS_OF_REPAIR)
                                <div class="btn btn-warning {{($repair->bus && $repair->bus->status == $key ? ' btn-selected' : '')}}"
                                     id="status_{{$key}}" onclick="selectStatus('{{$key}}')">{{$status}}</div>
                            @endif
                            @if($key == \App\Models\Bus::STATUS_REPAIR)
                                <div class="btn btn-danger {{($repair->bus && $repair->bus->status == $key ? ' btn-selected' : '')}}"
                                     id="status_{{$key}}" onclick="selectStatus('{{$key}}')">{{$status}}</div>
                            @endif
                        @endforeach

                        <p class="error-block"></p>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <button type="submit" class="btn btn-primary"><i
                                class="fa fa-dot-circle-o"></i> {{  trans('admin.filter.save') }} </button>

                    @if($repair->id)
                        @if($repair->order_outfit && $repair->order_outfit->id)
                            <a href="{{route('admin.repair_orders.order_outfits.edit',[$repair->id, $repair->order_outfit->id])}}"
                               class="btn btn-success"><i
                                        class="fa fa-dot-circle-o"></i> {{  trans('admin.repair_orders.order_outfit') }}
                            </a>
                        @else
                            <a href="{{route('admin.repair_orders.order_outfits.create',$repair->id)}}"
                               class="btn btn-success"><i
                                        class="fa fa-dot-circle-o"></i> {{  trans('admin.repair_orders.order_outfit') }}
                            </a>
                        @endif

                        <a href="{{route('admin.repair_orders.complete',$repair->id)}}" class="btn btn-danger"><i
                                    class="fa fa-dot-circle-o"></i> {{  trans('admin.repair_orders.buttons.complete_without_repair') }} </a>
                    @endif
                </div>
                    <div class="col-md-3"></div>

                    {!! Form::close() !!}

            </div>
        </div>
        <div class="ibox-footer">
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('select#select_repair_type_in_create option').first().attr('disabled', 'disabled');
            $('select#select_bus_status_in_create option').first().attr('disabled', 'disabled');
            $('select#carSelectInRepair option').first().attr('disabled', 'disabled');
            $('select#departmentSelect option').first().attr('disabled', 'disabled');
        })

        function selectStatus(key) {
            $(".btn-selected").removeClass('btn-selected');
            $("#status_" + key).addClass('btn-selected');
            $("input[name='bus_status']").val(key);
        }
    </script>
@endsection
