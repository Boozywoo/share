<div class="form-group">
    {!! Form::label($data['text']['name'], trans("admin_labels.{$data['text']['name']}"), ['class' => "col-md-4"]) !!}
    <div class="col-md-4">
        {!! Form::text($data['text']['name'], $data['text']['value'],['class' => "form-control"] ) !!}
        <p class="error-block"></p>
    </div>
    <div class="col-md-4">
        {!! Form::select($data['select']['name'], $data['select']['values'], $data['select']['selected'], ['class' => "form-control"]) !!}
        <p class="error-block"></p>
    </div>
</div>