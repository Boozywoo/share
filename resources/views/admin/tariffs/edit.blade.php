@extends('panel::layouts.main')

@section('title', $tariff->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> Вернуться назад</a>
@endsection

@section('main')
    {!! Form::model($tariff, ['route' => 'admin.'. $entity . '.store', 'class' => "ibox form-horizontal js_form-ajax js_form-ajax-reset"])  !!}
    {!! Form::hidden('id') !!}
    <div class="ibox-content">
        <h2>{{ $tariff->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}</h2>
        <div class="hr-line-dashed"></div>
        <div class="row">
            <div class="col-md-6">
                @php(array_unshift($busTypes, trans('admin.buses.not_sel')))
                {{ Form::panelText('name') }}
                {{
                    Form::panelSelect('tariff_direction_id', $tariff_directions,
                        ['class' => 'form-control'])
                }}
                {{
                    Form::panelSelect('bus_type_id', $busTypes,$tariff->id ? $tariff->bus_type_id : null,
                        ['class' => 'form-control js_tariff_change_type', 'id' => 'id_tariff_bus_type_select'])
                }}
                {{
                    Form::panelSelect('type', $types, $tariff->id ? $tariff->type : null,
                        ['class' => 'form-control js_tariff_change_type', 'id' => 'id_tariff_type_select'])
                }}

                <div class="tariff_route_group @empty($routes) hidden @endempty">
                    {{
                        Form::panelSelect('route_id', $routes ? $routes : [],
                            [
                                'class' => 'form-control',
                            ])
                    }}
                    {{
                        Form::panelSelect('revert_route_id', $routes ? $routes : [],
                            [
                                'class' => 'form-control'
                            ])
                    }}
                </div>
                {{ Form::panelSelect('status', trans('admin.tariffs.statuses')) }}

            </div>
            <div class="col-md-6">
            </div>
        </div>
    </div>
    <div class="ibox-footer">
        {{ Form::panelButton() }}
    </div>
    {!! Form::close() !!}
@endsection