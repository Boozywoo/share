<script>
    var parts = [];

</script>
<div class="ibox-content-item">
    @foreach($repairSpareParts as $key=>$sparePart)
        @if($sparePart->first())
            <script>
                parts.push({{$sparePart->first()->spare_part_id}});

            </script>
            {!! Form::open(['route' => ['admin.'. $entity . '.store', $repairOrder->id], 'class' => " form-horizontal js_form-ajax js_form-update-data js_form-mass"])  !!}

            <div class="row spare_part_row">
                {!! Form::hidden('spare_part_id', $key) !!}
                <div class="col-sm-3">{{$sparePart->first()->item ? $sparePart->first()->item->name: ''}}</div>
                <div class="col-sm-2">{{$sparePart->first()->item && $sparePart->first()->item->parent ? $sparePart->first()->item->parent->name: ''}}</div>
                <div class="col-sm-2">
                    <input type="number" onchange="changePart({{$key}})" name="count" id="count_{{$key}}"
                           value="{{$sparePart->first()->count}}"
                           class="form-control">
                </div>
                <div class="col-sm-3">
                    <select class="form-control" name="status" onchange="changePart({{$key}})"
                            id="status_{{$key}}">
                        @foreach($statuses as $status)
                            @if($status == $sparePart->first()->status)
                                <option value="{{$status}}"
                                        selected>{{__('admin.'.$entity.'.statuses.'.$status)}}</option>
                            @else
                                <option value="{{$status}}">{{__('admin.'.$entity.'.statuses.'.$status)}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2 td-actions">
                    <button class="btn btn-sm btn-primary " data-toggle="tooltip"
                            title="{{ trans('admin.filter.edit') }}">
                        <i class="fa fa-save"></i>
                    </button>
                    <div class="btn btn-sm btn-warning " data-toggle="tooltip"
                         onclick="showArea({{$key}})"
                         title="{{ trans('admin.filter.show') }}">
                        <i class="fa fa-angle-down"></i>
                    </div>
                    <a href="{{route('admin.'.$entity.'.destroy', [$repairOrder->id, $key])}}"
                       class="btn btn-sm btn-danger js_panel_confirm js_update-data" method="delete"
                       data-toggle="tooltip"
                       title="{{ trans('admin.filter.delete') }}">
                        <i class="fa fa-trash-o"></i>
                    </a>
                </div>
            </div>
            {!! Form::close() !!}
            <div class="spare_part_info_area" style="display: none" id="area_{{$key}}">
                <div class="row">
                    <div class="col-sm-3">{{__('admin_labels.user_id')}}</div>
                    <div class="col-sm-1">{{__('admin_labels.count')}}</div>
                    <div class="col-sm-3">{{__('admin_labels.status')}}</div>
                    <div class="col-sm-4">{{__('admin_labels.updated_at')}}</div>

                </div>
                @foreach($sparePart as $item)
                    <div class="row">

                        <div class="col-sm-3">{{$item->user ? $item->user->name : ''}}</div>
                        <div class="col-sm-1">{{$item->count}}</div>
                        <div class="col-sm-3">{{__('admin.'.$entity.'.statuses.'.$item->status)}}</div>
                        <div class="col-sm-4">{{$item->created_at->format('Y.m.d H:i:s')}}</div>
                    </div>
                @endforeach
            </div>
        @endif
    @endforeach
    <div class="row text-center">
        <button class="btn  btn-primary" onclick="massUpdate()" data-toggle="tooltip"
                title="{{ trans('admin.filter.save') }}">
            {{__('admin.filter.save')}} <i class="fa fa-save"></i>
        </button>
    </div>
    <div class="hr-line-dashed"></div>
    <div class="btn-group ">
        @if(!in_array('ordered',$finishedStatuses) && !in_array('in_process',$finishedStatuses) && in_array('finished',$finishedStatuses))
            <button class="btn btn-success js_finish_repair"
                    data-href="{{route('admin.repair_orders.finish', $repairOrder->id)}}" data-redirect="{{route('admin.repair_orders.index')}}"
                    data-status="{{\App\Models\Repair::STATUS_OF_REPAIR}}" data-question="{{__('admin.repair_orders.end_question')}}" data-bus-status=""
            >
                {{__('admin.repair_orders.complete')}}
            </button>
        @endif

        @if(in_array('ordered',$finishedStatuses) && !in_array('in_process',$finishedStatuses))
            <button class="btn btn-warning js_finish_repair"
                    data-href="{{route('admin.repair_orders.finish', $repairOrder->id)}}" data-redirect="{{route('admin.repair_orders.index')}}"
                    data-status="{{\App\Models\Repair::STATUS_WAIT}}" data-question="{{__('admin.repair_orders.wait_question')}}" data-bus-status="{{\App\Models\Bus::STATUS_OF_REPAIR}}"
            >
                {{__('admin.repair_orders.waiting')}}
            </button>
            <button class="btn btn-danger js_finish_repair"
                    data-href="{{route('admin.repair_orders.finish', $repairOrder->id)}}" data-redirect="{{route('admin.repair_orders.index')}}"
                    data-status="{{\App\Models\Repair::STATUS_WAIT}}" data-question="{{__('admin.repair_orders.wait_question')}}" data-bus-status="{{\App\Models\Bus::STATUS_REPAIR}}"
            >
                {{__('admin.repair_orders.waiting')}}
            </button>
        @endif
    </div>


</div>
<script>
    var changed_parts = [];

    function massUpdate() {
        let all = [];

        $(".js_form-mass").each(function (key, value) {
            let array = $(value).serializeArray();
            let data = {};
            array.forEach(function (item) {
                if (item.name != '_token') {
                    data[item.name] = item.value;
                }
            });
            if (changed_parts.indexOf(parseInt(data.spare_part_id)) >= 0) {
                all.push(data);
            }
        });
        $.ajax({
            url: "{{route('admin.'.$entity.'.storeMass', $repairOrder->id)}}",
            method: 'post',
            data: {all},
            success: function (data) {
                if (data.result == 'success') {
                    window.showNotification(data.message, 'success');
                    changed_parts = [];
                    updateData();
                } else {
                    window.showNotification(data.message, 'error');
                }
            }
        })
    }

    function changePart(id) {
        changed_parts.filter(function (val) {
            return val != id;
        })
        changed_parts.push(id);
    }

    $(document).ready(function () {
        $("#spare-part-select option").prop('disabled','');
        parts.forEach(function (part) {
            $('#part_' + part).prop('disabled', 'disabled');
        });
        $("#spare-part-select").select2();
    });

</script>