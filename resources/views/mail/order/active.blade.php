@extends('mail.layouts.main')

@section('content')

    <h2>Подтверждение о бронировании</h2>

    <p>

        Спасибо что воспользовались нашими услугами <br>
        Ваша бронирование успешно создано<br>
        @date($order->created_at) {{ $order->created_at->format('H:i') }} <br>
        Номер вашего билета: {{ $order->slug }}
    </p>

    @include('mail.order.partials.info')
@endsection