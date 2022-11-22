{!! Form::model($tour, ['route' => 'admin.tours.copyStore', 'class' => 'ibox-content form-horizontal js_form-ajax js_form-ajax-popup js_form-ajax-table js_form-current-page js_tours-from', 'data-wrap' => '.js_tour-edit-info', 'data-wrap-sub' => '.js_tour-edit-template']) !!}
{!! Form::hidden('id', $tour->id) !!}

<button type="button" class="close" data-dismiss="modal">
    <span aria-hidden="true">&times;</span>
    <span class="sr-only">Close</span>
</button>

<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#rentEdit">
            <h2>
                {{ trans('admin.tours.copy') }}
            </h2>
        </a>
    </li>
</ul>

<div class="tab-content">
    <div id="rentEdit" class="tab-pane fade in active">
        <div class="hr-line-dashed"></div>
        <div class="row">
            <div class="col-md-6">
                {{ Form::panelText('date_start', $tour->date_start ? $tour->date_start->format('d.m.Y') : Carbon\Carbon::now()->format('d.m.Y'), '', ['class' => "form-control js_datepicker"]) }}
                {{ Form::panelText('time_start', $tour->time_start ? $tour->prettyTimeStart : Carbon\Carbon::now()->format('H:i'), '', ['class' => "form-control time-mask"]) }}

                {{ Form::panelText('date_finish', $tour->date_finish ? $tour->date_finish->format('d.m.Y') : Carbon\Carbon::now()->format('d.m.Y'), '', ['class' => "form-control js_datepicker"]) }}
                {{ Form::panelText('time_finish', $tour->time_finish ? $tour->prettyTimeFinish : Carbon\Carbon::now()->addHour()->format('H:i'), '', ['class' => "form-control time-mask"]) }}
            </div>
        </div>
        <div class="hr-line-dashed"></div>
    </div>
</div>
{{ Form::panelButton() }}
{!! Form::close() !!}


