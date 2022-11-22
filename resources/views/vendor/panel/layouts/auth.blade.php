@extends('panel::root')

@section('body_class', 'gray-green-bg')
@section('body_bg', 'bg-image-road default_login-page_bg')

@section('root')
    @yield('auth')
@stop
