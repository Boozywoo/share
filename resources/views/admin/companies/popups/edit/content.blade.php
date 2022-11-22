{!! Form::model($company, ['route' => 'admin.'. $entity . '.store', 'class' => 'ibox-content form-horizontal js_form-ajax js_form-ajax-popup js_form-ajax-table js_form-current-page js_tours-from', 'data-wrap' => '.js_tour-edit-info', 'data-wrap-sub' => '.js_tour-edit-template']) !!}
{!! Form::hidden('id', $company->id) !!}
<button type="button" class="close" data-dismiss="modal">
    <span aria-hidden="true">&times;</span>
    <span class="sr-only">Close</span>
</button>
<h2>{{ $company->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}</h2>
<div class="hr-line-dashed"></div>
@include('admin.companies.editContent')
<div class="hr-line-dashed"></div>
{{ Form::panelButton() }}
{!! Form::close() !!}

