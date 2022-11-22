@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.title'))

@section('actions')
    @if (!Auth::user()->IsMethodist)
        <span data-url="{{ route('admin.companies.showPopup') }}" class="btn btn-sm btn-primary" data-toggle="modal"
              data-target="#popup_tour-edit"><span class="fa fa-plus"></span>
            {{ trans('admin.companies.add_button') }}
        </span>
        <span data-url="{{ route('admin.agreements.showPopup') }}" class="btn btn-sm btn-primary" data-toggle="modal"
              data-target="#popup_tour-edit"><span class="fa fa-plus"></span>
            {{ trans('admin.agreements.add_button') }}
        </span>
        <span data-url="{{ route('admin.'. $entity . '.showPopup') }}" class="btn btn-sm btn-primary" data-toggle="modal" data-backdrop="static"
              data-target="#popup_tour-edit"><span class="fa fa-plus">
            </span> {{ trans('admin.'. $entity . '.add_button') }}
        </span>
        <a href="{{ route('admin.'. $entity . '.schedule') }}"><span data-url="" class="btn btn-sm btn-primary"><span
                        class="fa fa-car"></span> {{ trans('admin.schedules.title') }}</span></a>
    @endif
@endsection

@section('main')
    <div class="row">
        <div class="col-md-4">
            <div class="ibox">
                <div class="ibox-content">
                    @include('admin.'. $entity . '.index.filter')
                </div>
            </div>
        </div>
        <div class="col-md-8">
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
