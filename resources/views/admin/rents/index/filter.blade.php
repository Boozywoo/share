{!! Form::open(['class' => 'js_table-search text-center', 'method' => 'get']) !!}
<div class="form-group dib">
    <div class="js_datepicker" data-date-start-date="-" data-date="{{ request('date') ? request('date') : Carbon\Carbon::now()->format('d.m.Y') }}"></div>
    {!! Form::hidden('date', request('date') ? request('date') : Carbon\Carbon::now()->format('d.m.Y'), ['class' => 'form-control js_table-reset-no']) !!}
</div>
<div id="test2">

</div>
<div class="form-group">
    {!! Form::select('bus_id', $buses, request('bus_id'), ['class' => "form-control"]) !!}
</div>
<div class="form-group">
    {!! Form::select('driver_id', $drivers, request('driver_id'), ['class' => "form-control"]) !!}
</div>
{!! Form::close() !!}