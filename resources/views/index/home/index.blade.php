@extends('index.layouts.main')

@section('meta_description', '')

@section('top')
    @include('index.home.partials.reservation')
    @include('index.home.partials.advantages')
@endsection

@section('content')
    <div class="item-page mainPage"></div>
    @include('index.home.partials.schedule_block')
    @if (env('RETURN_TICKET'))
        @include('index.home.partials.return_reservation')
        @include('index.home.partials.schedule_block_return')
    @endif

    {{--@include('index.home.partials.info')--}}
    @include('index.home.partials.sales')
    @include('index.home.partials.attention')
@endsection