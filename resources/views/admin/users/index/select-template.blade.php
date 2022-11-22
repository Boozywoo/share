{{ Form::panelSelect('company_id' ,$userCompanies, null , ['class' => 'form-control js_driver-select js_template-change' ,'id' => 'companySelect', 'data-url' => route('admin.users.getDepartments'), 'data-url1' => route('admin.users.getPositions'), 'data-url2' => route('admin.users.getSuperiors'), 'data-wrapper' => 'js_template-input']) }}
<div class="js_act_templates-content">
    <div class="js_template">
        @if(!empty($departments))
            {{ Form::panelSelect('department_id' ,$departments, $user->department_id?$user->department_id: null) }}
        @endif
    </div>
    <div class="js_template_positions">
        @if(!empty($positions))
            {{ Form::panelSelect('position_id' ,$positions, $user->position_id?$user->position_id: null) }}
        @endif
    </div>
    <div class="js_template_superiors">
        @if(!empty($superiors))
            {{ Form::panelSelect('superior_id' ,$superiors, $user->superior_id?$user->superior_id: null) }}
        @endif
    </div>
</div>
