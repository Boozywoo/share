<div class="form-group margin-disable">
    {!! Form::select('department_id', $departments, ($repair->department_id ? $repair->department_id :null), ['class' => "form-control ibox-content-item js_department_select",'placeholder' => __('admin_labels.department_id'),'data-url' => route('admin.repair_orders.getDepartmentsCarsView',['repair_id' => $repair]), 'id' => 'departmentSelect' ]) !!}
    <p class="error-block"></p>
</div>

<div class="js_act_templates-content">
    <div class="js_cars_template">
        @include('admin.repair_orders.index.cars-select')
    </div>
</div>
<div class="js_act_templates-content">
    <div class="js_cards_template">
        @include('admin.repair_orders.index.select-cards')
    </div>
</div>


