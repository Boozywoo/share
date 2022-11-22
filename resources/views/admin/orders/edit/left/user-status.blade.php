@if ($client)
    @dump($client->status_id)
    {!! Form::panelSelect('status_id', App\Models\Status::SelectStatuses(), $client ? $client->status_id : 0,
        ['class'        => 'form-control  js_orders-client-status_is',
        'data-url'      => route('admin.clients.change_status'),
        'data-current'  => $client ? $client->status_id : 0,
        ], false) !!}

    {{ Form::panelText('date_social', $client->date_social != null ? date('d.m.Y', strtotime($client->date_social)) : '', 'js_datepicker js_orders-client-date_social',
    [   'class'     => 'form-control js_datepicker js_orders-client-date_social',
        'data-url'  =>route('admin.clients.change_date_social'),
        ],false) }}
@endif