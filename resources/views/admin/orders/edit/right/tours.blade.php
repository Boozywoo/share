<div class="js_table-wrapper">
    @if(isset($order) && !empty($order->old_places) && count($order->old_places))
        <b>Предыдущие места:</b> <br>
        <span class="text-info">Кол-во мест:</span> {{ $order->old_places['count_places'] }} <br>
        <span class="text-info">Номер:</span>
        @foreach($order->old_places['places'] as $place)
            {{ $place['number'] }} @if (!$loop->last) , @endif
        @endforeach
    @endif
    @include('admin.orders.edit.right.table')
</div>
<div class="js_table-pagination">
    @include('admin.partials.pagination', ['paginator' => $tours])
</div>