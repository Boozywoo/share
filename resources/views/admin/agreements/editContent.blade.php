<div class="row">
    <div class="col-md-6">
        {{ Form::panelText('number') }}
    </div>
    <div class="col-md-6">
        {{ Form::panelText('name') }}
    </div>
    <div class="col-md-6">
        {{ Form::panelText('limit') }}
    </div>
    <div class="col-md-6">
        {{ Form::panelText('date', $agreement->date ? $agreement->date->format('d.m.Y') : '', 'js_datepicker') }}
    </div>
    <div class="col-md-6">
        {{ Form::panelText('expended') }}
    </div>
    <div class="col-md-6">
        {{ Form::panelText('date_start', $agreement->date_start ? $agreement->date_start->format('d.m.Y') : '', 'js_datepicker') }}
    </div>
    <div class="col-md-6">
        {{ Form::panelSelect('service_company_id',$companyServices) }}
    </div>
    <div class="col-md-6">
        {{ Form::panelText('date_end', $agreement->date_end ? $agreement->date_end->format('d.m.Y') : '', 'js_datepicker') }}
    </div>
    <div class="col-md-6">
        {{ Form::panelSelect('customer_company_id',$companyCustomers, $agreement ? $agreement->customer_company_id : null) }}
    </div>
    <div class="col-md-6">
        {{ Form::panelTextarea('description') }}
    </div>
    <div class="form-group">
        <label for="cities" class="col-md-1">{{trans('admin.tariffs.title')}}</label>
        <div class="col-md-11">
            <select class="js_input-select2 col-md-12" name="tariffs[]" multiple="multiple">
                @foreach ($tariffs as $id => $tariff)
                    <option @if (in_array($id, $agreementTariffs)) selected @endif value="{{$id}}">
                        {{-- DONTWORK --}}
                        {{$tariff}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>