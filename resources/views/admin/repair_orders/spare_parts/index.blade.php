@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.title'). $repairOrder->order_outfit->id)


@section('actions')
    {{--    <a href="{{ route('admin.'. $entity . '.create') }}"--}}
    {{--       class="btn btn-info">{{__('admin.repair_orders.create_order_btn')}}</a>--}}

    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> {{
    trans('admin.filter.back') }}</a>
@endsection
@section('main')
    <div class="ibox {{ $wrapperColor }}">
        <div class="ibox-content">
            <div class="hr-line-dashed"></div>
            {!! Form::open(['route' => ['admin.'. $entity . '.store', $repairOrder->id], 'class' => " form-horizontal js_form-ajax js_form-update-data"])  !!}

            @include('admin.repair_orders.spare_parts.table')

            <div class="hr-line-dashed"></div>

            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6 ibox-content-area">

                    <div class="new_part_row">
                        <select class="form-control" name="spare_part_id" id="spare-part-select">
                            <option value="0" selected
                                    disabled>{{__('admin.repair_orders.spare_parts.choose_spare_part')}}</option>

                            @foreach($spareParts as $part)

                                @if($part->has('active_childs'))
                                    <optgroup label="{{$part->name}}">
                                        @foreach($part->active_childs as $child)
                                            <option id="part_{{$child->id}}"
                                                    value="{{$child->id}}">{{$child->name}}</option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="new_part_row">
                        <span>{{__('admin_labels.count')}}</span>
                        <input type="number" name="count" value="1" class="form-control">
                    </div>
                    <div class="new_part_row">
                        <button class="btn btn-primary">{{__('admin.filter.save')}}</button>
                    </div>
                </div>
                <div class="col-sm-3"></div>

            </div>
            {!! Form::close() !!}
            <div class="hr-line-dashed"></div>

        </div>

        <div class="ibox-content">
            @include('admin.repair_orders.spare_parts.filter')
            <div class="hr-line-dashed"></div>

            <div class="js_table-wrapper" id="data-area">
                @include('admin.repair_orders.spare_parts.templates.parts-template')
            </div>
        </div>

    </div>

    <script>
        function updateData(data) {
            let url = new URL(window.location.href)

            $.ajax({
                url: "{{route('admin.'.$entity.'.content', $repairOrder->id)}}" + url.search,
                method: 'GET',
                success: function (data) {
                    $('#data-area').html(data);
                    $("#spare-part-select").val(0);
                    $('#spare-part-select').trigger('change');
                }
            });
        }

        function saveSparePart(id) {
            let data = {
                status: $('#status_' + id).val(),
                spare_part_id: id,
                count: $('#count_' + id).val(),
            };
            $.ajax({
                url: '/admin/repair_orders/{{$repairOrder->id}}/spare_parts',
                method: 'POST',
                data: data,
                success: function (res) {
                    console.log(res);
                }
            });
        }


        function showArea(id) {
            $('#area_' + id).toggle();
        }

        $(document).ready(function () {

            $("#spare-part-select").select2();

        })

    </script>
@endsection
