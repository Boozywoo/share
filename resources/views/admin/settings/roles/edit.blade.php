@extends('panel::layouts.main')

@section('title', $role->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i>{{
    trans('admin.filter.back') }}</a>
@endsection
@section('main')
    {!! Form::model($role, ['route' => 'admin.'. $entity . '.store', 'class' => 'ibox form-horizontal js_form-ajax js_form-ajax-reset'])  !!}
    {!! Form::hidden('id') !!}
    {!! Form::hidden('company_id', $company) !!}
    <div class="ibox-content">
        <h2>
            {{ $role->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}
        </h2>
        <div class="hr-line-dashed"></div>
        <div class="row">
            <div class="col-md-6">

                <div class="row">
                    <div class="col-md-12">
                        {{ Form::panelText('name') }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {{ Form::panelText('slug', $role->slug , 'name') }}
                    </div>
                </div>
                @foreach($separatePermissions as $permission )
                    <div class="row">
                        <div class="col-xs-3">
                            {!! Form::hidden("permissions[$permission->id]", false) !!}
                            {{Form::onOffCheckbox("permissions[$permission->id]", $role->permissions->find($permission->id) ? 1 : 0 )}}
                        </div>
                        <div class="col-xs-9">
                            <label for="permissions[{{$permission->id}}]">{{$permission->name}}</label>
                        </div>
                    </div>
                @endforeach

            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-xs-3">

                        {{Form::onOffCheckbox('select_all')}}
                    </div>
                    <div class="col-xs-9">
                        <label for="select_all"> Select All
                        </label>
                    </div>
                </div>

                @foreach($permissions as $permission )
                    <div class="row">
                        <div class="col-xs-3">
                            {!! Form::hidden("permissions[$permission->id]", false) !!}
                            {{Form::onOffCheckbox("permissions[$permission->id]", $role->permissions->find($permission->id) ? 1 : 0 )}}
                        </div>
                        <div class="col-xs-9">
                            <label for="permissions[{{$permission->id}}]">{{$permission->name}}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="ibox-footer">
        {{ Form::panelButton() }}
    </div>
    {!! Form::close() !!}
    <script>
        $('#select_all-').change(function (e) {
            var select_all = $('input[name^="permissions"]');
            select_all.prop('checked',!select_all.prop("checked"))
            // console.log($('[name^="permissions"]').toggle());
        });
    </script>
@endsection
