{!! Form::open(['route' => 'admin.sms.send', 'class' => 'ibox-content form-horizontal js_form-ajax js_form-ajax-popup js_form-ajax-table']) !!}
{!! Form::hidden('orderId', $orderId) !!}
<button type="button" class="close" data-dismiss="modal">
    <span aria-hidden="true">&times;</span>
    <span class="sr-only">Close</span>
</button>
<h2>Выслать смс</h2>
<div class="hr-line-dashed"></div>
<div class="row">
    <div class="col-md-12">
        {{ Form::panelTextarea('message') }}

    </div>
</div>
{{ Form::panelButton() }}
{!! Form::close() !!}