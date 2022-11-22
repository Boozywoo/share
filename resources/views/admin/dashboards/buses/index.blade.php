@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.list'))

@section('actions')

@endsection

@section('main')

    <div id="vue-shell">
        <buses-dashboard
                :buses="{{json_encode($buses)}}"
                :fields="{{json_encode($fields)}}"
                :field-data="{{json_encode($fieldData)}}"
        ></buses-dashboard>
    </div>
@endsection