//Pusher.logToConsole = true;

var pusher = new Pusher('c3a62f6331e2e8f12695', {
    cluster: 'eu',
    encrypted: true
});

var channel = pusher.subscribe('my-channel');
channel.bind('my-event', function(data) {
    if ((window.user_sip == data.sip || data.sip == 'all') && data.app_url == APP_URL)
    {
        //window.open('http://'+ window.location.hostname +'/admin/orders/create?incomming_phone='+data.number);
        $.easyAlert({
            'message': '<a><div class="text-center text-uppercase"><b>Входящий звонок: '+data.message+'</b></div></a>' ,
            'alertType': data.type,
            'link_page': 'https://'+ window.location.hostname +'/admin/tours?status=active&incomming_phone=' + data.number,
            'position': "t c",
            globalMinWidth: '400px',
            clickToHide: true,
            autoHide: true,
            time: data.time_show*1000,
            showAnimation: 'fade',
            showDuration: 300,
            hideAnimation: 'fade',
            hideDuration: 300,
            globalSpace: 5,
            complete: null,
            clicked: null,
            hidden:null
        });
    }
});
