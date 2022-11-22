@php($timeHash = time())
<script src="{{ asset('assets/panel/js/panel.js') }}"></script>
<script src="{{ elixir('assets/admin/js/libs.js').'?'.$timeHash }}"></script>
<script src="{{ elixir('assets/admin/js/datetimepicker.js').'?'.$timeHash }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.0/chart.min.js"></script>
<script src="{{ elixir('assets/admin/js/scripts.js').'?'.$timeHash }}"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAN67x01Vtwzd3XUnoDerz_GKwPiU_QfTA"></script>

<script src="{{asset('assets/vue.js').'?'.$timeHash}}"></script>
@stack('scripts')
{{--<script src="{{ asset('assets/index/js/markup/api_yandex.js') }}"></script>--}}
