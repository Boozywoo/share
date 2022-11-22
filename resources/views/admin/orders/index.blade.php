@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.title'))

@section('actions')
    <!-- <a href="{{ route('admin.'. $entity . '.create') }}" class="btn btn-sm btn-primary pjax-link"><span class="fa fa-plus"></span> {{ trans('admin.'. $entity . '.add_button') }}</a> -->
@endsection

@section('main')
    <div class="row">
        <div class="col-md-3">
            <div class="ibox">
                <div class="ibox-content">
                    @include('admin.'. $entity . '.index.filter')
                </div>
            </div>
        </div>
        <div  class="col-md-9">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="js_table-wrapper">
                        @include('admin.'. $entity . '.index.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection