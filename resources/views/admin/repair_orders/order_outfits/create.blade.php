@extends('panel::layouts.main')

@section('title',  $orderOutfit->id ? trans('admin.'. $entity . '.edit'). ' №'. $orderOutfit->id : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i>
        {{ trans('admin.filter.back') }}
    </a>
@endsection

@section('main')

    <div class="ibox form-horizontal {{ $wrapperColor }}">
        <div class="ibox-content">
            <div class="row ">
                @if($orderOutfit->id)
                    {!! Form::model($orderOutfit, ['route' => ['admin.'. $entity . '.update', $repairOrder->id,$orderOutfit->id], 'class' => " form-horizontal js_form-ajax js_form-ajax-redirect",'method' => 'put'])  !!}
                @else
                    {!! Form::model($orderOutfit, ['route' => ['admin.'. $entity . '.store', $repairOrder->id], 'class' => " form-horizontal js_form-ajax js_form-ajax-redirect"])  !!}

                @endif
                {!! Form::hidden('bus_id', $repairOrder->bus_id) !!}
                {!! Form::hidden('id', $repairOrder->id) !!}

                <div class="col-md-2">
                </div>

                <div class="col-md-8 ibox-content-area ">
                    <h2 class="text-center">{{__('admin.'.$entity.'.edit') . ($orderOutfit->id ? ' №'. $orderOutfit->id: '')}}</h2>

                    @if(!empty($repairOrder))
                        <div class="row">
                            @include('admin.repair_orders.order_outfits.repair-order-template')
                        </div>
                    @endif

                    <div class="col-md-3 form-group margin-disable">
                        <label for="date_from" class="control-label">{{__('admin_labels.date_from')}}</label>
                        {!! Form::text('date_from',$orderOutfit->date_from ? $orderOutfit->date_from->format('d.m.Y') : null,['class' => 'form-control js_datepicker ibox-content-item ibox-content-item-text','placeholder' => __('admin_labels.date_from'), "autocomplete"=>"off"])!!}
                        <p class="error-block"></p>
                    </div>
                    <div class="col-md-3 form-group margin-disable">
                        <label for="date_to" class="control-label">{{__('admin_labels.date_to')}}</label>
                        {!! Form::text('date_to',$orderOutfit->date_to ? $orderOutfit->date_to->format('d.m.Y') : '',['class' => 'form-control js_datepicker ibox-content-item ibox-content-item-text','placeholder' => __('admin_labels.date_to'), "autocomplete"=>"off"])!!}
                        <p class="error-block"></p>
                    </div>
                    <div class="col-md-3 form-group margin-disable">
                        <label for="odometer"
                               class="control-label">{{__('admin.repair_orders.order_outfits.odometer')}}</label>
                        {{--                        <br>--}}
                        {{--                        <span class="yellow-bg">{{$repairOrder->bus->odometer}} km</span>--}}
                        @if($orderOutfit->id)
                            {!! Form::number('odometer',
                            $orderOutfit->bus_variable ? $orderOutfit->bus_variable->odometer :
                            ($repairOrder->bus && $repairOrder->bus->getLastVariables() && $repairOrder->bus->getLastVariables()->odometer ? $repairOrder->bus->getLastVariables()->odometer : 0),
                            ['class' => 'form-control ibox-content-item','placeholder' => __('admin.repair_orders.order_outfits.odometer'),'disabled' => 'disabled']) !!}
                        @else
                            {!! Form::hidden('min_odometer', $repairOrder->bus && $repairOrder->bus->getLastVariables() && $repairOrder->bus->getLastVariables()->odometer ? $repairOrder->bus->getLastVariables()->odometer : 0) !!}
                            {!! Form::number('odometer', $repairOrder->bus && $repairOrder->bus->getLastVariables() && $repairOrder->bus->getLastVariables()->odometer ? $repairOrder->bus->getLastVariables()->odometer : 0,['class' => 'form-control ibox-content-item','placeholder' => __('admin.repair_orders.order_outfits.odometer')]) !!}
                        @endif
                        <p class="error-block"></p>
                    </div>
                    <div class="col-md-3 form-group margin-disable">
                        <label for="fuel" class="control-label">{{__('admin.repair_orders.order_outfits.fuel')}}</label>

                        @if($orderOutfit->id)
                            {!! Form::number('fuel',
                            $orderOutfit->bus_variable ? $orderOutfit->bus_variable->fuel :
                            ($repairOrder->bus && $repairOrder->bus->getLastVariables() && $repairOrder->bus->getLastVariables()->fuel ? $repairOrder->bus->getLastVariables()->fuel : 0),
                            ['class' => 'form-control ibox-content-item','placeholder' => __('admin.repair_orders.order_outfits.fuel'),'disabled' => 'disabled']) !!}
                        @else
                            {!! Form::number('fuel', $repairOrder->bus && $repairOrder->bus->getLastVariables() && $repairOrder->bus->getLastVariables()->fuel ? $repairOrder->bus->getLastVariables()->fuel : 0,['class' => 'form-control ibox-content-item','placeholder' => __('admin.repair_orders.order_outfits.fuel')]) !!}
                        @endif
                        <p class="error-block"></p>
                    </div>
                    {{--                    {!! Form::select('breakages', $breakages, null, ['class' => 'form-control ibox-content-item', 'placeholder' => __('admin.repair_orders.order_outfits.repair_reasons')]) !!}--}}
                    <div class="form-group ibox-content-item">

                        <select class="form-control" name="" id="breakages-select">
                            <option disabled
                                    selected>{{__('admin.repair_orders.order_outfits.repair_reasons')}}</option>

                            @foreach($carBreakages as $carBreakage)

                                @if($carBreakage->has('childs'))
                                    <optgroup label="{{$carBreakage->name}}">
                                        @foreach($carBreakage->childs as $child)
                                            <option id="breakage_{{$child->id}}"
                                                    value="{{$child->id}}">{{$child->name}}</option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="ibox-content-item" id="breakages-area">
                    </div>
                    {{Form::hidden('breakages',null, ['id' => 'breakages-list'])}}
                    <div class="form-group margin-disable">

                        {{ Form::textarea('comment',null,['class' => 'form-control ibox-content-item','placeholder' => __('admin.repair_orders.fields.additional_data')]) }}
                    </div>
                    <div class="form-group margin-disable">

                        <button type="submit" class="btn btn-primary ibox-content-item center-block">
                            <i class="fa fa-dot-circle-o"></i> {{trans('admin.filter.save') }} </button>
                    </div>
                </div>
                <div class="col-md-2">
                </div>

                {!! Form::close() !!}

            </div>
        </div>
        <div class="ibox-footer">
        </div>
    </div>
    <script>
        var breakages = [];

    </script>
    <script>
        function removeBreakages(id) {
            breakages = breakages.filter((item) => {
                return item.id != id;
            });
            $('#breakage_'+id).prop('disabled', '');
            $("#breakages-select").select2();

            updateList();
        }

        function updateList() {

            $('#breakages-list').val(JSON.stringify(breakages.map((item) => item.id)));
            let list_html = '';
            breakages.forEach((item) => {
                console.log(breakages)
                if (item.name && !item.text) {
                    item.text = item.name;
                }
                list_html += '<div class="list-item">' + item.text + '<div class="list-item-remove" onclick="removeBreakages(' + item.id + ')">x</div></div>';
            });
            $('#breakages-area').html(list_html);
        }

        $(document).ready(function () {


            $("#breakages-select").select2();

            $('#breakages-select').on("select2:select", function (e) {
                let selected_item = e.params.data;
                let element = e.params.data.element;
                breakages = breakages.filter((item) => {
                    if (item.id == selected_item.id) {
                        return false;
                    }
                    return true;
                });
                breakages.push(selected_item);
                $('#breakage_'+selected_item.id).prop('disabled','disabled');
                $("#breakages-select").select2();
                updateList();
                this.selectedIndex = 0;

            });


        })
        let item;
    </script>
    @if($orderOutfit->breakages)
        @foreach($orderOutfit->breakages as $breakage)
            <script>
                item = {!! json_encode($breakage) !!};
                breakages.push(item);
                $('#breakage_'+item.id).prop('disabled','disabled');
                updateList();
            </script>
        @endforeach
    @endif

@endsection
