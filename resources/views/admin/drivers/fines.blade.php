@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.fines'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i class="fa fa-chevron-left"></i> {{
    trans('admin.filter.back') }}</a>
    <a href="{{route ('admin.'. $entity . '.add_fine', $driver)}}" class="btn btn-sm btn-primary pjax-link"><span class="fa fa-plus"></span> {{ trans('admin.'. $entity . '.add_fine') }}</a>

@endsection

@section('main')
    <div class="ibox">
        <div class="ibox-content">
            <div class="js_table-wrapper">
                @if($driver->fines)
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                            <tr>
                                <th>{{trans('admin.drivers.date')}}</th>
                                <th>{{trans('admin.users.sum')}}</th>
                                <th>{{trans('admin.auth.desc')}}</th>
                                <th>{{trans('admin.drivers.type')}}</th>
                                <th>{{trans('admin.drivers.paid')}}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($driver->fines as $fine)
                                <tr>
                                    <td>
                                        {{$fine->date}}
                                    </td>
                                    <td>
                                        {{$fine->sum}}
                                    </td>
                                    <td>
                                        {{$fine->description}}
                                    </td>
                                    <td>
                                        {{$fine->type ? trans('admin.drivers.type_fines')[$fine->type] : 'Не определен'}}
                                    </td>
                                    <td>
                                        <i data-toggle="tooltip" title="" class="text-{{$fine->is_pay ? 'info' : 'danger' }} fa fa-money" data-original-title="{{$fine->is_pay ?  '' : 'не '}}оплачен"></i>
                                    </td>
                                    <td class="td-actions">
                                        <a href="{{route ('admin.'. $entity . '.edit_fine',['driver' => $driver, 'fine' => $fine])}}" class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title="{{trans('admin.filter.edit')}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">{{trans('admin.users.nothing')}}</p>
                @endif
            </div>
        </div>
@endsection