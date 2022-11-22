
@section('title', trans('admin.auth.registration-title'))

<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #676a6c;
    }

</style>

<div class="js-register" style="padding: 30px">
    <h3 class="title text-black-50">{{ trans('admin.auth.registration-title') }}</h3>
    {!! Form::open(['route' => 'admin.auth.registration', 'class' => 'm-t js_form-ajax js_form-ajax-register']) !!}
    <div class="form-group">
        {!! Form::text('first_name',null,['class' => 'form-control', 'placeholder' => trans('admin_labels.fio')]) !!}
        <p class="text-left error-block"></p>
    </div>
    <div class="form-group">
        {!! Form::select('company_id', $companies, null, ['class' => 'js-select-search-single form-control','id' => 'company', 'onchange' => 'onCompanyChange()', 'placeholder' => trans('admin_labels.company_id')]) !!}        <p class="text-left error-block"></p>
    </div>
    <div class="form-group">
        {!! Form::select('department_id', [], null, ['class' => 'js-select-search-single form-control', 'id' => 'department', 'onchange' => 'onDepartmentChange()', 'placeholder' => trans('admin_labels.department_id')]) !!}
        <p class="text-left error-block"></p>
    </div>
    <div class="form-group" style="color: #222222">
        {!! Form::select('position_id', [], null, ['class' => 'form-control','id' => 'position', 'placeholder' => trans('admin_labels.position_id')]) !!}
        <p class="text-left error-block"></p>
    </div>
    <div class="form-group">
        {!! Form::text('phone',null,['class' => 'form-control', 'placeholder' => '72013914293']) !!}
        <p class="text-left error-block"></p>
    </div>
    <div class="form-group">
        {!! Form::text('email', request('email'), ['class' => 'form-control', 'placeholder' => 'Email']) !!}
        <p class="text-left error-block"></p>
    </div>
    <div class="form-group">
        {!! Form::password('password', ['class' => "form-control", 'placeholder' => trans('admin.auth.pass')]) !!}
        <p class="text-left error-block"></p>
    </div>
    <button type="submit" class="btn btn-warning block full-width m-b">{{ trans('admin.auth.apply') }}</button>
    {!! Form::close() !!}

    <div class="registrationscreen__question">
        {{trans('admin.auth.have-account')}}

        <a class="registrationscreen__registration-link js_form-ajax-back pjax-link" href="/admin/auth/login">
            {{trans('admin.auth.log_in')}}
        </a>
    </div>
    
</div>

<script>
    function onCompanyChange() {
        const companyId = document.getElementById('company').value;

        if (companyId === '') {
            return;
        }

        $.ajax({
            url: "/admin/auth/search-company",
            type: 'GET',
            dataType: 'json',
            data: {
                company_id: companyId,
            },
            success: (data) => select(data, '#department'),
        });

        $.ajax({
            url: "/admin/auth/company-position",
            type: 'GET',
            dataType: 'json',
            data: {
                company_id: companyId,
            },
            success: (data) => select(data, '#position'),
        });
    }
    function onDepartmentChange() {
        const departmentId = document.getElementById('department').value;
        const companyId = document.getElementById('company').value;

        if (departmentId === '') {
            return;
        }

        $.ajax({
            url: "/admin/auth/search-director",
            type: 'GET',
            dataType: 'json',
            data: {
                department_id: departmentId,
            },
            success: (data) => hidden(data),
        });
    }
    function hidden(data){
        console.log(data)
        $('#form').append('<input type="hidden" name="superior_id" value="'+data.id+'" />');

    }
    function select(data, id){

        $.each(data, function (i, item) {
            $(id).append($('<option>', {
                value: i,
                text : item
            }));
        });

    }
</script>
