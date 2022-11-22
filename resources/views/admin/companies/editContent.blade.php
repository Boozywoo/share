<div class="row">
    <div class="col-md-6">
        {{ Form::panelText('name') }}
        {{ Form::panelText('responsible') }}
        {{ Form::panelText('position') }}
        <div class="form-group">
            <label for="email" class="col-md-4">{{trans('admin_labels.phone')}}</label>
            <div class="col-md-8">
                <input class="form-control" name="phone" type="text" value="{{$company->phone}}">
                <p class="error-block"></p>
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-md-4">{{trans('admin_labels.dop_tel')}}</label>
            <div class="col-md-8">
                <input class="form-control" name="phone_sub" type="text" value="{{$company->phone_sub}}">
                <p class="error-block"></p>
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-md-4">{{trans('admin_labels.tel_resp')}}</label>
            <div class="col-md-8">
                <input class="form-control" name="phone_resp" type="text" value="{{$company->phone_resp}}">
                <p class="error-block"></p>
            </div>
        </div>
        {{--{{ Form::panelText('phone', $company->editPhone, 'js_panel_input-phone') }}--}}
        {{--{{ Form::panelText('phone_sub', $company->editPhoneSub, 'js_panel_input-phone') }}--}}
        @if($company->id)
            {{ Form::panelSelect('status', trans('admin.companies.statuses')) }}
            {{ Form::panelSelect('reputation', trans('admin.companies.reputations')) }}
        @endif
        <div class="form-group">
            <label for="email" class="col-md-4">{{trans('admin.auth.req')}}</label>
            <div class="col-md-8">
                <textarea class="form-control" name="requisites" rows="4">{{$company->requisites}}</textarea>
                <p class="error-block"></p>
            </div>
        </div>
        <div style="margin-bottom: 15px;" class="row">
            {!! Form::hidden("is_customer", false) !!}
            <label style="font-weight: 500; text-align: right;" for="is_customer" class="col-md-4">
                {{trans('admin_labels.is_customer')}}
            </label>
            <div class="col-md-8">
                {{ Form::onOffCheckbox('is_customer') }}
            </div>
        </div>
        <div style="margin-bottom: 15px;" class="row">
            {!! Form::hidden("is_carrier", false) !!}
            <label style="font-weight: 500; text-align: right;" for="is_carrier" class="col-md-4">
                {{trans('admin_labels.is_carrier')}}
            </label>
            <div class="col-md-8">
                {{ Form::onOffCheckbox('is_carrier') }}
            </div>
        </div>
        <div style="margin-bottom: 15px;" class="row">
            {!! Form::hidden("dispatcher", false) !!}
            <label style="font-weight: 500; text-align: right;" for="dispatcher" class="col-md-4">
                {{trans('admin_labels.dispatcher')}}
            </label>
            <div class="col-md-8">
                {{ Form::onOffCheckbox('dispatcher') }}
            </div>
        </div>
    </div>
    <div class="col-md-6">
    </div>
</div>