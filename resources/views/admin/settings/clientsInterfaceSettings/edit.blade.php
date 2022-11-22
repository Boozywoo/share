@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.title'))

@section('main')
    <div class="ibox">
        <div class="ibox-content">
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.edit.content')
            </div>
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.edit.frame')
            </div>
        </div>
    </div>
@endsection