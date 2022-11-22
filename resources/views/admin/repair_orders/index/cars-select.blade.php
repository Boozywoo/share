@if(!empty($buses))
    <div class="form-group margin-disable">
        {!! Form::select('bus_id', $buses, (!empty($repair) && $repair->bus_id ? $repair->bus_id :null), ['class' => "form-control ibox-content-item js_car_select",'placeholder' => __('admin_labels.car_id'), 'id' => 'carSelectInRepair', 'data-url' => route('admin.repair_orders.getCarCardsView') ]) !!}
        <p class="error-block"></p>
    </div>
    <script>
        $('select#carSelectInRepair option').first().attr('disabled','disabled');
    </script>

@endif
