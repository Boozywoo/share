{!! Form::model('setting', ['route' => ['admin.settings.setToursFieldsPopup'], 'class' => 'ibox-content form-horizontal js_form-ajax js_form-ajax-popup']) !!}
    <button type="button" class="close" data-dismiss="modal">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    <h2>{{ trans('admin_labels.show_fields_in_tours') }}</h2>
    <div class="hr-line-dashed"></div>

    <b>{{trans('admin_labels.show_arrival_time')}}</b>
    <div class="col-md-1 checkbox">
        {!! Form::hidden('show_arrival_time', 0) !!}
        <input class="checkbox" @if($setting->show_arrival_time) checked
                @endif name="show_arrival_time" type="checkbox" value="1">
        <label for="show_arrival_time"></label>
        {{--{{Form::Checkbox('show_arrival_time') }}--}}
    </div>


    <div class="hr-line-dashed"></div>

    {{ Form::panelButton(trans('admin.buses.rent.send')) }}
{!! Form::close() !!}

