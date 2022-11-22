$(document).ready(function() {
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');

    if(dd < 15 && dd > 0) {
        setInterval(function() {
            $.get( "/admin/check_is_pay", function(data) {
    
                if(data.is_paid == false) {
                    $('.my-alert-warning').fadeIn(2000);
    
                    setTimeout(function() {
                        $('.my-alert-warning').fadeOut(2000);
                    }, 15000);
                }
            });
        }, 60000);
    } else {
        setInterval(function() {
            $.get( "/admin/check_is_pay", function(data) {
    
                if(data.is_paid == false) {
                    $('.my-alert-danger').fadeIn(2000);
    
                    setTimeout(function() {
                        $('.my-alert-danger').fadeOut(2000);
                    }, 15000);
                }
            });
        }, 60000);
    }
    
});
  
  
