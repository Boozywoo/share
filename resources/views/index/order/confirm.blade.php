@extends('index.layouts.main')

@section('title', trans('index.profile.confirm_of_pass'))

@section('content')
<div class="mainWidth ticketMainBlock backg" style="flex: 500">
    <p class="title">{{trans('index.order.code_was_sent')}}</p>
    {!! Form::open(['route' => 'index.order.do-confirm', 'class' => 'js_ajax-form enterStntCodeForm']) !!}
        {!! Form::text('code', null, ['class' => 'forCode', 'autofocus' => '', 'placeholder' => trans('index.order.enter_code')]) !!}
        <button type="submit" class="send">Ok</button>
    {!! Form::close() !!}
    <p class="title" style="margin-top: 20px"> {{trans('index.order.order_timer')}}
        <span id="timer-minutes">02 : </span>
        <span id="timer-seconds">00</span>
    </p>
</div>

@endsection

@push('scripts')
    <script>
        let timerSeconds = 120; // Секунд в таймере
        function setTimer(timer)
        {
            if (timer < 0) timer = 0;
            let minutes = Math.floor(timer/60);
            let seconds = Math.floor(timer - minutes*60);
            if(String(minutes).length > 1) {
                $('#timer-minutes').text(minutes + " : ");
            } else {
                $('#timer-minutes').text("0" + minutes + " : ");
            }
            if(String(seconds).length > 1) {
                $('#timer-seconds').text(seconds);
            } else {
                $('#timer-seconds').text("0" + seconds);
            }
        }
        $(document).ready(function()
        {
            setInterval(function(){
                timerSeconds = timerSeconds - 1;
                setTimer(timerSeconds);
                if(timerSeconds <= 0){
                    $('input[type="submit"]').attr("disabled","disabled");
                    $.ajax({
                        url: "/order/confirm",
                        type: "POST",
                        data: { code : "false" },
                    }).fail(function(){
                        window.location.replace("/");
                    }).done(function(data){
                        if (data.result == 'error') {
                            toastr.error(data.message);
                            window.location.replace("/");
                        }
                    });
                }
            }, 1000);
        });
    </script>
@endpush