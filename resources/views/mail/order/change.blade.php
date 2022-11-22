@extends('mail.layouts.main')

@section('content')

    <h2>Вниманиие! Ваша бронь изменена</h2>

    <p>
        В виду нештатной ситуации мы были вынуждены изменить данные вашего электронного билета <br>
        Номер вашего билета: {{ $order->slug }}
    </p>

    @include('mail.order.partials.info')
@endsection