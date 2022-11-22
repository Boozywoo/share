@extends('index.layouts.main')

@section('title', trans('index.order.print_ticket'))

@section('main')
    <div class="mainWidth ticketMainBlock">
        @include('index.order.partials.ticket')
    </div>
@endsection

@push('scripts')
    <script>
        window.print();
    </script>
@endpush