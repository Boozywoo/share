@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.title'))


@section('actions')
    {{--    <a href="{{ route('admin.'. $entity . '.create') }}" class="btn btn-sm btn-primary pjax-link"><span class="fa fa-plus"></span> {{ trans('admin.'. $entity . '.create_btn') }}</a>--}}
    <a href="{{ route('admin.'. $entity . '.create', ['status' => 'order']) }}"
       class="btn btn-info">{{__('admin.repair_orders.create_order_btn')}}</a>

    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> {{
    trans('admin.filter.back') }}</a>
@endsection
@section('main')
    <div class="ibox {{ $wrapperColor }}">
        <div class="ibox-content">
            <div class="row">
                {!! Form::open(['class' => 'form-inline js_table-search', 'method' => 'get', 'id' => 'filtering-form']) !!}

                {!! Form::hidden('type',request('type')) !!}
                {!! Form::hidden('status',request('status')) !!}

                @foreach($filterStatuses['STATUSES'] as $filterStatus)
                    <div class="btn col-md-3 btn-default"
                         onclick="filtering('status','{{$filterStatus}}')">{{__('admin.'.$entity.'.car_repair_filter.'.$filterStatus)}}</div>
                @endforeach
                @foreach($filterStatuses['TYPES'] as $filterType)
                    <div class="btn col-md-3 btn-default"
                         onclick="filtering('type','{{$filterType}}')">{{__('admin.'.$entity.'.car_repair_filter.'.$filterType)}}</div>
                @endforeach
                <div class="btn col-md-3 btn-default"
                     onclick="filtering('all')">{{__('admin.'.$entity.'.car_repair_filter.all')}}
                </div>

                {!! Form::close() !!}
            </div>
            {{--            <div class="hr-line-dashed"></div>--}}
            {{--            <div class="row ">--}}
            {{--                <a href="{{ route('admin.'. $entity . '.create', ['status' => 'order']) }}"--}}
            {{--                   class="btn btn-info">{{__('admin.repair_orders.create_order_btn')}}</a>--}}
            {{--                <a href="{{ route('admin.'. $entity . '.create', ['status' => 'repair']) }}"--}}
            {{--                   class="btn btn-info">{{__('admin.repair_orders.create_btn')}}</a>--}}
            {{--            </div>--}}
            <div class="hr-line-dashed"></div>
                        @include('admin.repair_orders.index.filter')
            <div class="hr-line-dashed"></div>

        </div>


        <div class="ibox-content">
            <div class="js_table-wrapper">
                @include('admin.'. $entity . '.index.table')
            </div>
        </div>
                <div class="ibox-footer js_table-pagination">
                    @include('admin.partials.pagination', ['paginator' => $repairs])
                </div>
    </div>

    <script>
        function filtering(key, value) {

            $('input[type="hidden"][name="type"]').val('');
            $('input[type="hidden"][name="status"]').val('');
            $('input[type="hidden"][name="' + key + '"]').val(value);
            $('#filtering-form').trigger('submit');
        }
    </script>
@endsection
