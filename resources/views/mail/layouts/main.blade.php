@extends('mail.root')

@section('main')

    @include('mail.partials.header')

    @yield('content')

    @include('mail.partials.footer')

@endsection