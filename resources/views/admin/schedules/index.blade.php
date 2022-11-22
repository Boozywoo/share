@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.title'))

@section('actions')
    <span class="btn btn-sm btn-primary js_change_sched_price" data-title="<h2>Изменить цену всех расписаний</h2>">
        <span class="fa fa-ticket"></span> {{ trans('admin.'. $entity . '.edit_price') }}
    </span>
    <a href="{{ route('admin.'. $entity . '.create') }}" class="btn btn-sm btn-primary"><span class="fa fa-plus"></span> {{ trans('admin.'. $entity . '.add_button') }}</a>
@endsection

@section('main')
    <div class="ibox">
        <div class="ibox-content">
            <h2>{{ trans('admin.'. $entity . '.list') }}</h2>
            <div class="hr-line-dashed"></div>
            @include('admin.'. $entity . '.index.filter')
            <div class="hr-line-dashed"></div>
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.index.table')
            </div>
        </div>
        <div class="ibox-footer js_table-pagination">
            @include('admin.partials.pagination', ['paginator' => $schedules])
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/js/bootbox.min.js') }}"></script>
@endpush
