@extends('index.layouts.main')

@section('title', trans('index.schedules.title'))

@section('content')
    @include('index.schedules.partials.schedule_block')
@endsection