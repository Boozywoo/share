@extends('mail.layouts.main')

@section('content')

    <h2>Ваша бронь отменена</h2>

    <p>
        Код отмененного билета: {{ $order->slug }}
    </p>

     @include('mail.order.partials.info')
@endsection