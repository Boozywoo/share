<style type="text/css">
    .blink_me {
        -webkit-animation-name: blinker;
        -webkit-animation-duration: 3s;
        -webkit-animation-timing-function: linear;
        -webkit-animation-iteration-count: infinite;

        -moz-animation-name: blinker;
        -moz-animation-duration: 3s;
        -moz-animation-timing-function: linear;
        -moz-animation-iteration-count: infinite;

        animation-name: blinker;
        animation-duration: 3s;
        animation-timing-function: linear;
        animation-iteration-count: infinite;
    }

    @-moz-keyframes blinker {
        0% { opacity: 1.0; }
        50% { opacity: 0.0; }
        100% { opacity: 1.0; }
    }

    @-webkit-keyframes blinker {
        0% { opacity: 1.0; }
        50% { opacity: 0.0; }
        100% { opacity: 1.0; }
    }

    @keyframes blinker {
        0% { opacity: 1.0; }
        50% { opacity: 0.0; }
        100% { opacity: 1.0; }
    }
</style>

<div class="text-center">
    <H1 class="blink_me text-danger"><b>ВНИМАНИЕ!</b></H1>
    <H3>В системе бронирования (всегда) указана полная стоимость поездки от Могилёва до Гомеля</H3>
    <H3>Стоимость промежуточной поездки уточняйте у водителя или оператора.</H3>
    <H3>Оплата проезда осуществляется при посадке на рейс.</H3>
</div>