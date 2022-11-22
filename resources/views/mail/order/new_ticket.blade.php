@extends('mail.layouts.min')

@section('content')

    <h2>{{ trans('emails.new_ticket.title') }}</h2>

    <p>
        {{ trans('emails.new_ticket.message') }}<br>
        <br>
    </p>
@endsection