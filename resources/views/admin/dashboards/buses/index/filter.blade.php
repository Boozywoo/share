<div class="modal inmodal in" id="db_bus-filter" tabindex="-1" role="dialog" aria-hidden="true"
     style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content-body" style="min-height: 400px">
            <div class="" style="position: relative; padding: 10px 25px;">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>

                <div class="row">
                    <div class="col-sm-6">
                        <h2>{{ trans('admin_labels.filter') }}</h2>
                        @if(!request()->has('hide_filter'))
                            <div class="col-sm-3">
                                {{__("admin_labels.select_all")}} :
                            </div>
                            <div class="col-sm-2">
                                <div class="onoffswitch filter-switch" style="vertical-align: bottom">
                                    {!! Form::checkbox('fields[]', 'select_all', count($fields['all']) == count($selectedFields), ['class' => 'card-checkbox',
                                        'style' => 'display: none', 'id' => 'field_select_all'])  !!}
                                    {!! Form::labelHtml('field_select_all', '<span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>',
                                        ['class' => 'onoffswitch-label']
                                    ) !!}
                                </div>
                            </div>
                        @endif

                    </div>
                    <div class="col-sm-6" style="text-align: right">
                        <a href="" class="btn btn-default js_filter_table-reset"><span
                                    class="fa fa-ban"></span> {{trans('admin.filter.drop')}}</a>
                        <button class="btn btn-primary btn-filter-submit"
                                type="button">{{__('admin.filter.save')}}</button>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                {!! Form::open(['onsubmit' => 'return ;','class' => 'js_table-submit', 'method' => 'post','id' => 'filter-table','data-link' => route('admin.dashboards.buses.index')]) !!}

                @foreach($fields['all'] as $field)
                    <div class="row filter-fields" id="filter-field-{{$field}}">

                        <div class="col-sm-2">
                            @if(!request()->has('hide_filter'))
                                <div class="onoffswitch filter-switch" style="vertical-align: bottom">
                                    {!! Form::checkbox('fields[]', $field, in_array($field,$selectedFields), ['class' => 'card-checkbox fields-check',
                                        'style' => 'display: none', 'id' => 'field_'. $field])  !!}
                                    {!! Form::labelHtml('field_'. $field, '<span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>',
                                        ['class' => 'onoffswitch-label']
                                    ) !!}
                                </div>
                            @endif
                        </div>
                        <div class="col-sm-3" style="padding-top: 8px;">
                            {{__("admin_labels.$field")}} :
                        </div>
                        <div class="col-sm-7">

                            @if(in_array($field,$fields['constants']))
                                {!! Form::panelSelect2("filter[$field]", uniqueByKey($allBuses,$field,$field,'constants',__('admin.dashboards.'.$field)), request('filter')[$field] ?? '' , $field, __("admin_labels.$field")) !!}
                            @elseif(in_array($field,$fields['relations']))
                                {!! Form::panelSelect2("filter[$field]", uniqueByKey($allBuses,$field,$field,'relations'), request('filter')[$field] ?? '' , $field, __("admin_labels.$field")) !!}
                            @elseif(in_array($field,$fields['dates']))
                                {!! Form::panelSelect2("filter[$field]", uniqueByKey($allBuses,$field,$field,'dates'), request('filter')[$field] ?? '' , $field, __("admin_labels.$field")) !!}
                            @elseif(in_array($field,$fields['custom']))
                                {!! $field == 'departments' && !empty($departments) ? Form::panelSelect2("filter[$field]", $departments, request('filter')[$field] ?? '' , $field, __("admin_labels.$field")) : '' !!}
                                {!! $field == 'bus_drivers' && !empty($busDrivers) ? Form::panelSelect2("filter[$field]", $busDrivers, request('filter')[$field] ?? '' , $field, __("admin_labels.$field")) : '' !!}
                                {!! $field == 'color' && !empty($colors) ? Form::panelSelect2("filter[$field]", $colors, request('filter')[$field] ?? '' , $field, __("admin_labels.$field")) : '' !!}
                                {!! $field == 'customer_director' && !empty($customerPersonalities) ? Form::panelSelect2("filter[$field]", $customerPersonalities, request('filter')[$field] ?? '' , $field, __("admin_labels.$field")) : '' !!}
                                {!! $field == 'customer_company' && !empty($customerCompanies) ? Form::panelSelect2("filter[$field]", $customerCompanies, request('filter')[$field] ?? '' , $field, __("admin_labels.$field")) : '' !!}
                                {!! $field == 'customer_department' && !empty($customerDepartments) ? Form::panelSelect2("filter[$field]", $customerDepartments, request('filter')[$field] ?? '' , $field, __("admin_labels.$field")) : '' !!}
                                {!! $field == 'fact_referral' && !empty($customerDepartments) ? Form::panelSelect2("filter[$field]", $customerDepartments, request('filter')[$field] ?? '' , $field, __("admin_labels.$field")) : '' !!}
                            @else
                                {!! Form::panelSelect2("filter[$field]", uniqueByKey($allBuses,$field,$field,''), request('filter')[$field] ?? '' , $field, __("admin_labels.$field")) !!}
                            @endif
                        </div>
                    </div>

                @endforeach



                {!! Form::close() !!}
            </div>

        </div>
    </div>
</div>
