@if($order->id || (isset($tour) && $tour->id))
    @include('admin.orders.edit.right.tour', ['tour' => isset($tour) && $tour->id ? $tour : $order->tour, 'client' => $order->client])
@else
    @include('admin.orders.edit.right.tours')
@endif