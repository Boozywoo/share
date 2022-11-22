
{!! Form::open(['route' => ['admin.'. $entity . '.exportReport'], 'method' => 'get', 'class' => 'ibox-content form-horizontal form-inline']) !!}
    <button type="button" class="close" data-dismiss="modal">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    <h2>{{ trans('admin.'. $entity . '.report') }}</h2>
    <div class="hr-line-dashed"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="input-daterange input-group js_table-reset-no" id="datepicker">
                {!! Form::text('date_from', request('date_from', Carbon\Carbon::now()->subMonths(1)->format('Y-m-d')), ['class' => "input-sm form-control", 'readonly']) !!}
                <span class="input-group-addon">{{trans('admin.filter.to')}}</span>
                {!! Form::text('date_to', request('date_to', Carbon\Carbon::now()->format('Y-m-d')), ['class' => "input-sm form-control", 'readonly']) !!}
            </div>
            {!! Form::select('type_pay', trans('admin.orders.pay_types'), request('type_pay'), ['placeholder' => trans('admin.buses.sel_status'),'class' => "form-control"]) !!}

        </div>
    </div>
    <div class="hr-line-dashed"></div>
    {{ Form::panelButton(trans('index.profile.to_download')) }}
{!! Form::close() !!}

