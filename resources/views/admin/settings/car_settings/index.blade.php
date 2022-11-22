@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.title'))

@section('actions')
    <a href="{{ route('admin.'. $entity . '.car_colors.create') }}" class="btn btn-sm btn-primary pjax-link"><span class="fa fa-plus"></span> {{ trans('admin.'. $entity . '.car_colors.create_btn') }}</a>
    <a href="{{ route('admin.'. $entity . '.customer_persons.create') }}" class="btn btn-sm btn-primary pjax-link"><span class="fa fa-plus"></span> {{ trans('admin.'. $entity . '.customer_persons.create_btn') }}</a>
    <a href="{{ route('admin.'. $entity . '.customer_companies.create') }}" class="btn btn-sm btn-primary pjax-link"><span class="fa fa-plus"></span> {{ trans('admin.'. $entity . '.customer_companies.create_btn') }}</a>
    <a href="{{ route('admin.'. $entity . '.customer_departments.create') }}" class="btn btn-sm btn-primary pjax-link"><span class="fa fa-plus"></span> {{ trans('admin.'. $entity . '.customer_departments.create_btn') }}</a>
@endsection

@section('main')
    <div class="ibox">
        @include('admin.'. $entity . '.car_colors.table')
        @include('admin.'. $entity . '.customer_persons.table')
        @include('admin.'. $entity . '.customer_companies.table')
        @include('admin.'. $entity . '.customer_departments.table')
    </div>
@endsection