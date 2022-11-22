@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.title'))

@section('main')
    <div class="ibox">
        <div class="ibox-content">
            <h2>
                {{ trans('admin.'. $entity . '.list') }}
            </h2>
            <div class="hr-line-dashed"></div>
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.edit.content')
            </div>
        </div>
    </div>
@endsection