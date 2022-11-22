<div role="tabpanel" class="tab-pane active" id="general">
    <h2>{{trans('admin.pages.main')}}</h2>
    <div class="hr-line-dashed"></div>
    <div class="row">
        <div class="col-md-6">
            {{ Form::panelText('title') }}
        </div>
        <div class="col-md-6">
        </div>
    </div>
    {{ Form::panelTextarea('content', true, 'withTitle') }}
</div>