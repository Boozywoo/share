@extends('index.root')

@section('main')

    <div class="topBlock">
        @include('index.partials.header')
        @yield('top')
    </div>

    @yield('content')

    @include('index.partials.footer')

@stop