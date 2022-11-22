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
@php
    $orderOutfit = $repairOrder->order_outfit;
@endphp
<script>
    function showCardTable() {
        $("#card-line #caret-down").toggle();
        $("#card-line #caret-up").toggle();
        $("#card-table").toggle();
    }

</script>
<div style="position: relative; cursor: pointer; text-align: center" id="card-line">
    <h3 onclick="showCardTable()" style="display: inline-block">{{__('admin.repair_orders.fields.order_outfit')}}</h3>
    <div onclick="showCardTable()" class="btn-show" id="caret-down">
        <span class="fa fa-caret-down" style="font-size: 22px;color: #f8ac59;"></span>
    </div>
    <div onclick="showCardTable()" class="btn-show" id="caret-up" style="display: none">
        <span class="fa fa-caret-up" style="font-size: 22px; color: #f8ac59;"></span>
    </div>
    <a href="{{route('admin.repair_orders.order_outfits.edit',[$repairOrder,$repairOrder->order_outfit])}}"
       class="btn-show" id="card-open">
        <span class="fa fa-eye" style="font-size: 20px; color: #f8ac59;"></span>
    </a>
</div>
<div class="table-responsive">
    <table class="table table-condensed" id="card-table" style="display: none">
        <thead>
        <tr>
            <th>{{ trans('admin_labels.order_id') }}</th>
            <th>{{ trans('admin_labels.date_from') }}</th>
            <th>{{ trans('admin_labels.date_to') }}</th>
            <th>{{ trans('admin_labels.breakages') }}</th>
            <th>{{ trans('admin_labels.comment') }}</th>
        </tr>
        </thead>
        <tbody>

        <tr>
            <td>{{ $orderOutfit->id }}</td>
            <td>{{ $orderOutfit->date_from->format('d.m.Y') }}</td>
            <td>{{ $orderOutfit->date_to ? $orderOutfit->date_to->format('d.m.Y') : ''}}</td>
            <td>
                @foreach($orderOutfit->breakages as $breakage)
                    {{$breakage->name}} |
                @endforeach</td>
            <td>{{ $orderOutfit->comment }}</td>
        </tr>
        </tbody>
    </table>
</div>
