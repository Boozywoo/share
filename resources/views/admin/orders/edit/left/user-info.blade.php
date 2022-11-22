@if($client)
    @include('admin.orders.edit.left.user-statistic')
@endif
@php
    $route = \App\Models\Route::find(request('route_id'));
    if (isset($tour->route))$route = $tour->route;
    $fields = isset($route->required_inputs)? $route->required_inputs : '';
    $fields = explode(',',$fields);
@endphp
{{--@if(isset($tour) && $tour->rent && $client)
    {!! Form::panelSelect('company_id', $companies,$client ? $client->company_id : null, ['class' => "form-control"], false) !!}
    @php($agreements = \App\Models\Agreement::where('service_company_id', $tour->rent->company_carrier_id))
    @if ($client->company_id)
        @php($agreements = $agreements->where('customer_company_id', $client->company_id))
    @endif
    @if($agreements->count())
        @php($agreements = $agreements->get())
    @else
        @php(collect(['- Не выбран -']))
    @endif
    {!! Form::panelSelect('agreement_id', $agreements->pluck('name', 'id'),$tour->rent->agreement_id, ['class' => "form-control"], false) !!}
    @if($agreements->count() == 1)
        @php($agreement = $agreements->first())
        @php($tariffs = $agreement->tariffs->where('bus_type_id', $agreement->bus_type_id)->pluck('name', 'id'))
        {!! Form::panelSelect('tariff_id', $tariffs,null, ['class' => "form-control"], false) !!}
    @endif
@endif--}}

{!! Form::hidden('client_id', $client ? $client->id : '') !!}
@if (in_array('last_name',$fields))
    {!! Form::panelText('last_name', $client ? $client->last_name : '', null, ['class' => "form-control js_orders-client-last_name"], false) !!}
@endif
{!! Form::panelText('first_name', $client ? $client->first_name : '', null, ['class' => "form-control js_orders-client-first_name"], false) !!}
@if (in_array('middle_name',$fields))
    {!! Form::panelText('middle_name', $client ? $client->middle_name : '', null, ['class' => "form-control js_orders-client-middle_name"], false) !!}
@endif
@if (in_array('card',$fields))
    {!! Form::panelText('card', $client ? $client->card : '', null, ['class' => "form-control js_orders-client-card"], false) !!}
@endif


@if(request('route_id')|| isset($tour))
    @if (in_array('passport',$fields))
        {!! Form::panelText('passport', $client ? $client->passport : '', null, ['class' => "form-control js_orders-client-passport"], false) !!}
    @endif
    @if (in_array('doc_type',$fields))
        {!! Form::panelSelect('doc_type', trans('admin_labels.doc_types'), $client ? $client->doc_type : null, ['class' => "form-control"], false) !!}
    @endif
    @if (in_array('doc_number',$fields))
        {!! Form::panelText('doc_number', $client ? $client->doc_number : '', null, ['class' => "form-control"], false) !!}
    @endif
    @if (in_array('country_id',$fields))
        {!! Form::panelSelect('country_id', trans('admin_labels.countries'), $client ? $client->country_id : 0, ['class' => "form-control"], false) !!}
    @endif
    @if (in_array('gender',$fields))
        {!! Form::panelSelect('gender', trans('admin_labels.genders'), $client ? $client->gender : null, ['class' => "form-control"], false) !!}
    @endif
    @if (in_array('birth_day',$fields))
        {{ Form::panelText('birth_day', $client ? date('d.m.Y', strtotime($client->birth_day)) : '', 'js_datepicker',
        [   'class'     => "form-control js_datepicker",
            ],false) }}
    @endif

    @if($tour->route && isset($taxiHistory) && count($taxiHistory))
        @if($tour->route->is_taxi)
            <div class="form-group">
                <label for="gender" class=" control-label">История поездок</label>
                <select class="form-control js-taxi-history">
                    @if(count($taxiHistory) == 1)
                        <option value='0'> </option>
                    @endif
                    @foreach($taxiHistory as $item)
                        <option value="{{ $item->id }}" data-from="{{ $item->stationFrom->name }}" data-to="{{ $item->stationTo->name }}">
                            {{ str_replace('улица', 'ул.', $item->stationFrom->name) }} - {{ str_replace('улица', 'ул.', $item->stationTo->name) }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
    @endif
@endif
