@extends('panel::layouts.main')

@section('title',  trans('admin.'. $entity . '.show').' №'.$repairOrder->id)

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i>
        {{trans('admin.filter.back') }}</a>
@endsection

@section('main')

    <div class="ibox form-horizontal {{ $wrapperColor }}">
        <div class="ibox-content">
            <div>
                @if(in_array($repairOrder->status, \App\Models\Repair::CLOSED_STATUSES))
                    <h4 class="font-bold">{{__('admin.repair_orders.statuses.'.$repairOrder->status)}}</h4>
                @else
                    <a href="{{route("admin.$entity.complete", $repairOrder->id)}}"
                       class="btn btn-warning">{{__('admin.repair_orders.buttons.complete_without_repair')}}</a>
                    {{--            <div class="btn btn-primary">{{__('admin.repair_orders.buttons.exit')}}</div>--}}
                    @if(!in_array('ordered',$finishedStatuses) && !in_array('in_process',$finishedStatuses) && in_array('finished',$finishedStatuses))
                        <button class="btn btn-success js_finish_repair"
                                data-href="{{route('admin.repair_orders.finish', $repairOrder->id)}}"
                                data-redirect="{{route('admin.repair_orders.index')}}"
                                data-status="{{\App\Models\Repair::STATUS_OF_REPAIR}}"
                                data-question="{{__('admin.repair_orders.end_question')}}" data-bus-status=""
                        >
                            {{__('admin.repair_orders.complete')}}
                        </button>
                    @endif

                    @if(in_array('ordered',$finishedStatuses) && !in_array('in_process',$finishedStatuses))
                        <button class="btn btn-warning js_finish_repair"
                                data-href="{{route('admin.repair_orders.finish', $repairOrder->id)}}"
                                data-redirect="{{route('admin.repair_orders.index')}}"
                                data-status="{{\App\Models\Repair::STATUS_WAIT}}"
                                data-question="{{__('admin.repair_orders.wait_question')}}"
                                data-bus-status="{{\App\Models\Bus::STATUS_OF_REPAIR}}"
                        >
                            {{__('admin.repair_orders.waiting')}}
                        </button>
                        <button class="btn btn-danger js_finish_repair"
                                data-href="{{route('admin.repair_orders.finish', $repairOrder->id)}}"
                                data-redirect="{{route('admin.repair_orders.index')}}"
                                data-status="{{\App\Models\Repair::STATUS_WAIT}}"
                                data-question="{{__('admin.repair_orders.wait_question')}}"
                                data-bus-status="{{\App\Models\Bus::STATUS_REPAIR}}"
                        >
                            {{__('admin.repair_orders.waiting')}}
                        </button>
                    @endif

                @endif
            </div>
            <div class="hr-line-dashed"></div>
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-8 ">{{__('admin.repair_orders.fields.repair_order')}}
                        №{{$repairOrder->id}}</div>
                    <div class="col-md-4"><a class="underline-text"
                                             href="{{route("admin.$entity.edit", $repairOrder->id)}}">{{$repairOrder->created_at->format('d.m.Y')}}</a>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="hr-line-dashed"></div>
                    <div class="col-md-8">{{__('admin.repair_orders.fields.order_outfit')}}</div>
                    <div class="col-md-4">
                        @if($repairOrder->order_outfit)
                            <a class="underline-text"
                               href="{{route('admin.'.$entity.'.order_outfits.edit',[$repairOrder->id,$repairOrder->order_outfit->id])}}">{{$repairOrder->order_outfit->created_at->format('d.m.Y')}}</a>
                        @else
                            @if(!in_array($repairOrder->status, \App\Models\Repair::CLOSED_STATUSES))
                            <a class="btn btn-primary"
                               href="{{route('admin.'.$entity.'.order_outfits.create', $repairOrder->id)}}">
                                {{__('admin_labels.create_btn')}}
                            </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="hr-line-dashed"></div>
                    <div class="col-md-8">{{__('admin.repair_orders.fields.diagnostic_card')}}</div>
                    <div class="col-md-4">
                        @if($repairOrder->diagnostic_card)
                            <a class="underline-text"
                               href="{{route('admin.'.$entity.'.diagnostic_cards.edit',[$repairOrder->id,$repairOrder->diagnostic_card->id])}}">{{$repairOrder->diagnostic_card->created_at->format('d.m.Y')}}</a>


                        @else
                            @if($repairOrder->order_outfit)
                                @if(!in_array($repairOrder->status, \App\Models\Repair::CLOSED_STATUSES))

                                    <a class="btn btn-primary"
                                       href="{{route('admin.'.$entity.'.diagnostic_cards.create', $repairOrder->id)}}">
                                        {{__('admin_labels.create_btn')}}
                                    </a>
                                @endif
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="hr-line-dashed"></div>
                    <div class="col-md-8">{{__('admin.repair_orders.fields.parts_list')}}</div>
                    <div class="col-md-4">
                        @if($repairOrder->diagnostic_card )
                            @if($repairOrder->spare_parts()->count() > 0)
                                <a class="underline-text"
                                   href="{{route('admin.'.$entity.'.spare_parts.index', $repairOrder->id)}}">
                                    {{$repairOrder->spare_parts()->oldest()->first()->created_at->format('d.m.Y')}}
                                </a>
                            @else
                                @if(!in_array($repairOrder->status, \App\Models\Repair::CLOSED_STATUSES))

                                    <a class="btn btn-primary"
                                       href="{{route('admin.'.$entity.'.spare_parts.index', $repairOrder->id)}}">
                                        {{__('admin.filter.add')}}
                                    </a>
                                @endif
                            @endif
                        @endif
                        {{--                        <div class="btn btn-primary">{{__('admin_labels.create_btn')}}</div>--}}
                    </div>
                </div>
            </div>
            {{--
                        <div class="row">
                            <div class="col-md-6">
                                <div class="hr-line-dashed"></div>
                                <div class="col-md-8">{{__('admin.repair_orders.fields.parts_received')}}</div>
                                <div class="col-md-4">
                                    --}}
            {{--                        <div class="btn btn-primary">{{__('admin_labels.create_btn')}}</div>--}}{{--

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="hr-line-dashed"></div>
                                <div class="col-md-8">{{__('admin.repair_orders.fields.parts_order')}}</div>
                                <div class="col-md-4">
                                    --}}
            {{--                        <div class="btn btn-primary">{{__('admin_labels.create_btn')}}</div>--}}{{--

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="hr-line-dashed"></div>
                                <div class="col-md-8">{{__('admin.repair_orders.fields.parts_installed')}}</div>
                                <div class="col-md-4">
                                    --}}
            {{--                        <div class="btn btn-primary">{{__('admin_labels.create_btn')}}</div>--}}{{--

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="hr-line-dashed"></div>
                                <div class="col-md-8">{{__('admin.repair_orders.fields.parts_return')}}</div>
                                <div class="col-md-4">
                                    --}}
            {{--                        <div class="btn btn-primary">{{__('admin_labels.create_btn')}}</div>--}}{{--

                                </div>
                            </div>
                        </div>
            --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="hr-line-dashed"></div>
                    <div class="col-md-8">{{__('admin.repair_orders.fields.repairs_completed')}}</div>
                    <div class="col-md-4">
                        @if(in_array($repairOrder->status, \App\Models\Repair::CLOSED_STATUSES) && !empty($repairOrder->date_end))
                            <div class="underline-text">{{$repairOrder->date_end->format('d.m.Y')}}</div>

                        @endif
                        {{--                        <div class="btn btn-primary">{{__('admin_labels.create_btn')}}</div>--}}
                    </div>
                </div>
            </div>
        </div>
        <div class="ibox-footer">
        </div>
    </div>

@endsection
