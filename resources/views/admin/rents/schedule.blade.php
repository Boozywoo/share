@extends('panel::layouts.main')
@section('title', trans('admin.'. $entity . '.title'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default"><i
                class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection
@section('main')
    @include('admin.rents.schedule.content')
@endsection



