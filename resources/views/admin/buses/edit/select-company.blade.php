{{ Form::panelSelect('company_id' , $companies, null ,
    [
        'class' => "form-control js_company-select " ,
        'id' => 'companySelect',
        'data-url' => route('admin.users.getDepartments'),
        'data-wrapper' => 'js_company-input'
    ]
) }}
<div class="js_act_department-content">
    <div class="js_department_select">
        @if(!empty($departments))
            {{ Form::panelSelect('department_id' ,$departments, $bus->department_id ? $bus->department_id: null) }}
        @endif
    </div>
</div>
