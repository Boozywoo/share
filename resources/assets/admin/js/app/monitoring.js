$(document).ready(function () {

    var busSpeeds = {};

    if($('meta[id="env_speed"]').attr('content') == true) {
        setInterval(() => {
            $.get('/admin/users/coordinate/is-high-speed', function(data) {
                
                if(busSpeeds[data.busId] != data.speed)
                {
                    busSpeeds[data.busId] = data.speed;
                    if (data.isExceeded) {
                        $.easyAlert( {
                            'message': data.message,
                            'alertType': 'danger',
                            'link_page': 'https://'+ window.location.hostname +'/admin/monitoring',
                            'position': 't c', 
                            'autoHide': true,
                        });
                    } 
                }
                
            });
        }, 1000 * 10);
        
        $("#setSpeed").click(function() {
            const highSpeed = $('input[name="high_speed"]').val();
            $.post("/admin/monitoring/sethighspeed", { highSpeed: highSpeed });
        });
    }   

});