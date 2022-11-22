    Pusher.logToConsole = true;

    function notice_func(message) {
    defaults = {
            'message': '<a href="http://vimi.asmebolt.ru/admin/orders/create?incomming_phone='+message+'">Входящий звонок: '+message+'</a>',
            'alertType': 'success',
            'position': "b r",
            globalMinWidth: '250px',
            clickToHide: true,
            autoHide: true,
            time: 30000,
            showAnimation: 'fade',
            showDuration: 300,
            hideAnimation: 'fade',
            hideDuration: 300,
            globalSpace: 5,
            complete: null,
            clicked: null,
            hidden: null
        };

        easyAlert(defaults);
         // The function returns the product of p1 and p2
}

    var pusher = new Pusher('c3a62f6331e2e8f12695', {
      cluster: 'eu',
      encrypted: true
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
      notice_func(data.message);
      //alert(data.message);
    });
