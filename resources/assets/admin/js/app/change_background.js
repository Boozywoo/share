$(document).ready(function () {
    // $("#ch_img").click(function() {
    //     $.post("/admin/change_background_image");
    // });
    let files;
    $('#ch_img_upload').on('change', function(){

        if(this.files.length!=0&&this.files.length!=null)
        {
            $('.wrapper-spinner').show();
            files = this.files[0];
            let formData = new FormData();
            formData.append('file_to_upload', files);
            $.ajax({
                type: 'POST',
                url: '/admin/change_background_image',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (data) {
                    $('.wrapper-spinner').hide();
                    document.location.reload();
                    // if(data.message) window.showNotification(data.message, data.result);
                    // if(data.view_success) $('[data-ajax=content-success]').html(data.view_success);
                },
                // error: function () {
                //     $('.wrapper-spinner').hide();
                // }
            });
        }


    });

});