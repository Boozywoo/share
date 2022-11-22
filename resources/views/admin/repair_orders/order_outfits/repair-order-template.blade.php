<style>
    .btn-show {
        display: inline-block;
        vertical-align: middle;
    }

    #card-open {
        position: absolute;
        right: 10px;
        top: 3px;
    }
</style>
<script>
    function showCardTable() {
        $("#card-line #caret-down").toggle();
        $("#card-line #caret-up").toggle();
        $("#card-table").toggle();
    }

</script>
<div style="position: relative; cursor: pointer; text-align: center" id="card-line">
    <h3 onclick="showCardTable()" style="display: inline-block">{{__('admin.repair_orders.fields.repair_order')}}</h3>
    <div onclick="showCardTable()" class="btn-show" id="caret-down">
        <span class="fa fa-caret-down" style="font-size: 22px;color: #f8ac59;"></span>
    </div>
    <div onclick="showCardTable()" class="btn-show" id="caret-up" style="display: none">
        <span class="fa fa-caret-up" style="font-size: 22px; color: #f8ac59;"></span>
    </div>
    <a href="{{route('admin.repair_orders.edit',[$repairOrder])}}"
       class="btn-show" id="card-open">
        <span class="fa fa-eye" style="font-size: 20px; color: #f8ac59;"></span>
    </a>
</div>
<div class="table-responsive">
    <table class="table table-condensed" id="card-table" style="display: none">
        <thead>
        <tr>
            <th>{{ trans('admin_labels.bus_id') }}</th>
            <th>{{ trans('admin_labels.state_number') }}</th>
            <th>{{ trans('admin_labels.garage_number') }}</th>
            <th>{{ trans('admin_labels.name') }}</th>
            <th>{{ trans('admin_labels.type') }}</th>
        </tr>
        </thead>
        <tbody>

        <tr>
            <td>{{ $repairOrder->bus->name }}</td>
            <td>{{ $repairOrder->bus->number}}</td>
            <td>{{$repairOrder->bus->garage_number}}</td>
            <td>{{$repairOrder->name}}</td>
            <td>{{__('admin.repair_orders.types.'.$repairOrder->type)}}</td>
        </tr>
        </tbody>
    </table>

</div>