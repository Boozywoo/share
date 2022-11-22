@extends('index.order.result') 

@section('content')
    @parent
    <form style="display: none;" id="payOrder" action="https://payment.webpay.by" method="post">
        <input type="hidden" name="*scart">
        <input type="hidden" name="wsb_version" value="2">
        <input type="hidden" name="wsb_storeid" value="{{$storeid}}">
        <input type="hidden" name="wsb_store" value="{{$order->tour->bus->company->name}}">
        <input type="hidden" name="wsb_order_num" value="{{$order->slug}}">
        <input type="hidden" name="wsb_test" value="{{env('PAY_TEST')}}">
        <input type="hidden" name="wsb_currency_id" value="{{ $currency }}">
        <input type="hidden" name="wsb_seed" value="order-{{$order->id}}">
        <input type="hidden" name="wsb_customer_name" value="{{$order->client->FIO()}}">
        <input type="hidden" name="wsb_service_date" value="@date($order->tour->date_start) {{trans('index.home.time')}}@time($order->station_from_time, 0) ">
        <input type="hidden" name="wsb_customer_address" value="г.{{ $order->stationFrom->city->name }} {{trans('index.order.station')}}: {{ $order->stationFrom->name }}">
        <input type="hidden" name="wsb_return_url" value="{{$url}}/order/pay/on_success_webpay">
        <input type="hidden" name="wsb_cancel_return_url" value="{{$url}}/order/pay/on_fail_webpay">
        <input type="hidden" name="wsb_notify_url" value="{{$url}}/order/notice_pay">
        <input type="hidden" name="wsb_email" value="{{$order->client->email}}">
        @if($order->return_order_id)
            @foreach($order->OrderPlaces as $orderPlace)
                @php($place = $orderPlace->number ? : $loop->iteration)
                <input type="hidden" name="wsb_invoice_item_name[]" value="{{trans('index.order.seat')}} {{$place}}">
                <input type="hidden" name="wsb_invoice_item_quantity[]" value="1">
                <input type="hidden" name="wsb_invoice_item_price[]" value="{{$orderPlace->price}}">
            @endforeach
            @foreach($order->returnOrder->OrderPlaces as $orderPlace)
                @php($place = $orderPlace->number ? : $loop->iteration)
                <input type="hidden" name="wsb_invoice_item_name[]" value="{{trans('index.order.seat')}} {{$place}}">
                <input type="hidden" name="wsb_invoice_item_quantity[]" value="1">
                <input type="hidden" name="wsb_invoice_item_price[]" value="{{$orderPlace->price}}">
            @endforeach
        @else
            @foreach($order->OrderPlaces as $orderPlace)
                @php($place = $orderPlace->number ? : $loop->iteration)
                <input type="hidden" name="wsb_invoice_item_name[]" value="{{trans('index.order.seat')}} {{$place}}">
                <input type="hidden" name="wsb_invoice_item_quantity[]" value="1">
                <input type="hidden" name="wsb_invoice_item_price[]" value="{{$orderPlace->price}}">
            @endforeach
            @if($order->addServices)
                @foreach ($order->addServices as $item)
                    <input type="hidden" name="wsb_invoice_item_name[]" value="{{ $item->name }}">
                    <input type="hidden" name="wsb_invoice_item_quantity[]" value="{{ $item->pivot->quantity}}">
                    <input type="hidden" name="wsb_invoice_item_price[]" value="{{$item->value*$item->pivot->quantity}}">
                @endforeach
            @endif
        @endif
        <input type="hidden" name="wsb_total" value="{{$price}}">
        <input type="hidden" name="wsb_signature" value="{{$signature}}">
        <input type="hidden" name="wsb_tax" value="0">
        {{--<input type="hidden" name="wsb_shipping_name" value="{{trans('index.order.sale_on_product')}}">
        <input type="hidden" name="wsb_shipping_price" value="0">
        <input type="hidden" name="wsb_discount_name" value="{{trans('index.order.cost_on_product')}}">
        <input type="hidden" name="wsb_discount_price" value="0">--}}
        <input type="submit" value="{{trans('index.order.buy')}}">
    </form>
@endsection

@push('scripts')
    <script>
        $('#payOrder').submit();
    </script>
@endpush