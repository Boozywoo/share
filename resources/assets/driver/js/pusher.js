$(document).ready(function(){
    let id = $('meta[id="tour_id"]').attr('content');
    let env = $('meta[id="env"]').attr('content');
    
    if(id !== undefined && env !== undefined) {
        let pusher = new Pusher(env, {
            cluster: 'eu',
            encrypted: true
        });

        var channel2 = pusher.subscribe('driver-taxi-channel2');
        var dialogs = [];
        let is_taxi = $('meta[id="is_taxi"]').attr('content');
        channel2.bind('new-taxi-order', function (data) {
            if (data.app_url == APP_URL && is_taxi == 1) {
                navigator.serviceWorker.register('/assets/driver/js/sw.js').then(function(registration) {
                    registration.showNotification("Такси", {
                        body: data.message,
                        icon: '/assets/admin/images/admin-logo.png'
                    });
                });

                dialogs[data.client_id] = bootbox.dialog({
                    title: "Новый заказ!",
                    message: "Заказ через 15 мин:<br> от "+data.from+"<br>до "+data.to,
                    closeButton: true,
                    className: 'js-taxi-order-'+data.client_id,
                    size: 'extra-large',
                    onEscape: true,
                    backdrop: true,
                    buttons: {
                        min5: {
                            label: '5 мин',
                            className: 'btn-success',
                            callback: function(){
                                taxiOrder(id, 5, data);
                            }
                        },
                        min10: {
                            label: '10 мин',
                            className: 'btn-success',
                            callback: function(){
                                taxiOrder(id, 10, data);
                            }
                        },
                        min15: {
                            label: '15 мин',
                            className: 'btn-success',
                            callback: function(){
                                taxiOrder(id, 15, data);
                            }
                        },
                        cancel: {
                            label: 'Пропускаю',
                            className: 'btn-dark'
                        }
                    },
                });
                window.setTimeout(function(){
                    dialogs[data.client_id].modal('hide');
                }, 25000);
            }
        });

        channel2.bind('close-taxi-order', function (data) {
            if (data.app_url == APP_URL) {
                dialogs[data.client_id].modal('hide');
            }
        });

        if (!("Notification" in window) ) {
            $.ajax({
                type: "GET",
                async: true,
                cache: true,
                url: "/driver/tours/passengers/" + id + "/get_id",
                success: function(ids) {
                    var was_pushed = 0;

                    if (ids !== null || ids !== '') {
                        if (was_pushed == 0) {
                            var channel = pusher.subscribe('driver-channel' + ids);
                            channel.bind('my-event', function(datas) {
                                was_pushed = 1;
                                window.location.reload();
                            });
                        }
                    }
                }
            });
    
        } else if (Notification.permission == "granted") {    
            $.ajax({
                type: "GET",
                async: true,
                cache: true,
                url: "/driver/tours/passengers/" + id + "/get_id",
                success: function(ids) {
                    var was_pushed = 0;

                    if (ids !== null || ids !== '') {
                        if (was_pushed == 0) {
                            var channel = pusher.subscribe('driver-channel' + ids);
                            channel.bind('my-event', function(datas) {
                                if ('serviceWorker' in navigator && 'PushManager' in window) {
                                    was_pushed = 1;

                                    navigator.serviceWorker.register('/assets/driver/js/sw.js').then(function(registration) {
                                        registration.showNotification(datas.message);
                                        window.location.reload();
                                    });
                                }
                            });
                        }

                    }
                }
            });
        } else {
            Notification.requestPermission().then(function (permission) {
                if (permission == "granted") {
                    $.ajax({
                        type: "GET",
                        async: true,
                        cache: true,
                        url: "/driver/tours/passengers/" + id + "/get_id",
                        success: function(ids) {
                            var was_pushed = 0;

                            if (ids !== null || ids !== '') {
                                if (was_pushed == 0) {
                                    var channel = pusher.subscribe('driver-channel' + ids);
                                    channel.bind('my-event', function(datas) {
                                        if ('serviceWorker' in navigator && 'PushManager' in window) {
                                            was_pushed = 1;
                                            if ('serviceWorker' in navigator && 'PushManager' in window) {
                                                was_pushed = 1;
            
                                                navigator.serviceWorker.register('/assets/driver/js/sw.js').then(function(registration) {
                                                    registration.showNotification(datas.message);
                                                    window.location.reload();
                                                });
                                            }
                                        }
                                    });
                                }

                            }
                        }
                    });
                }
            });
        }
    }
    
});

function taxiOrder(id, delay, data)    {
    $('.js_spinner-overlay').show();
    $('.background-spinner').show();
    $.post('/driver/tours/taxiorder/' + id + '/add', {
        tour_id: id,
        station_from_id: data.from_id,
        station_to_id: data.to_id,
        client_id: data.client_id,
        delay: delay,
        places: [],
    }).done(function(data) {
        $('.js_spinner-overlay').hide();
        $('.background-spinner').hide();
        window.location.reload();
    });
}
