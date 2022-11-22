{{ Form::panelSelect('department_id' ,$departments, null , ['class' => 'form-control js_department_select' ,'id' => 'departmentSelect','disabled' => 'disabled', 'data-url' => route('admin.repair_orders.getDepartmentsCarsView')]) }}
<div class="js_act_templates-content">
    <div class="js_cars_template">
        @if(!empty($buses))
            {{ Form::panelSelect('bus_id' ,$buses, null,['disabled' =>'disabled']) }}
        @endif
    </div>
</div>
