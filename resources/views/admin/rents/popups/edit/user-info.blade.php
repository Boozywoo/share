{!! Form::hidden('client_id', $client ? $client->id : '') !!}
@if($client)
    @include('admin.orders.edit.left.user-statistic')
@endif
{!! Form::panelText('last_name', $client ? $client->last_name : '', null, ['class' => 'form-control js_orders-client-last_name'], false) !!}
{!! Form::panelText('first_name', $client ? $client->first_name : '', null, ['class' => 'form-control js_orders-client-first_name'], false) !!}
{!! Form::panelText('middle_name', $client ? $client->middle_name : '', null, ['class' => 'form-control js_orders-client-middle_name'], false) !!}
{!! Form::panelText('passport', $client ? $client->passport : '', null, ['class' => 'form-control js_orders-client-passport'], false) !!}
