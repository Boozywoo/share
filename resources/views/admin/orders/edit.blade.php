@extends('panel::layouts.main')

@section('title', $order->id 
    ? trans('admin.'. $entity . '.edit') 
    : trans('admin.'. $entity . '.create')
)

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection

@section('main')
    <div class="row">
        <div class="col-md-4">
            @php($client = isset($order->client) ? $order->client : $client)
            @php($tour = isset($order->tour) ? $order->tour : $tour )
            @include('admin.orders.edit.left', ['required_inputs' => $required_inputs ?? []])
        </div>
        <div class="col-md-8">
            <div class="ibox">
                <div class="ibox-content js_orders-left">
                    @include('admin.orders.edit.right')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function()
        {
            $('.js_orders-from').on('submit', function () {
                if ($('#js_order_return').val() != '') {
                    $('.js_input_order_places').first().trigger('focusout');
                }
            });
            setTimeout (saveOrder, 1000);
        });
        function saveOrder() {
            if ($('#js_order_return').val() != '')  {
                $(".js_order_calculation").click();
            }
        }
    </script>
@endpush
