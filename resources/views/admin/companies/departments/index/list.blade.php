@extends('panel::layouts.main')

@section('title', trans('admin.'.$entity.'.single').' '. $department->name)

@section('actions')
        <a href="{{ route('admin.users.create', ['department_id' => $department->id,'company_id' => $company->id]) }}" class="btn btn-sm btn-primary pjax-link"><span
                    class="fa fa-plus"></span> {{ trans('admin.users.add_button') }}</a>
@endsection

@section('main')
    <div class="ibox">
        <div class="ibox-content">
            <h2>{{ trans('admin.'. $entity . '.index.list') }}</h2>
            <div class="hr-line-dashed"></div>
            {{--            @include('admin.'. $entity . '.index.filter')--}}
            <div class="hr-line-dashed"></div>
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.index.list.table')
            </div>
        </div>
        <div class="ibox-footer js_table-pagination">
            @include('admin.partials.pagination', ['paginator' => $users])
        </div>
    </div>
@endsection
