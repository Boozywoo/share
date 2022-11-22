<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Расписание</title>
    <link type="text/css" rel="stylesheet" href="{{asset('rent/css/main.css')}}"/>
    <link type="text/css" rel="stylesheet" href="{{asset('rent/css/timeSlider.css')}}"/>
    <script type="text/javascript" src="{{asset('rent/lib/jquery.js')}}"></script>
    <script type="text/javascript" src="{{asset('rent/lib/underscore.js')}}"></script>
    <script type="text/javascript" src="{{asset('rent/js/timeSlider.js')}}"></script>
</head>
<body>
<link rel="stylesheet" href="{{asset('rent/css/main.css')}}" type="text/css">
<div class="container-fluid">
    @include('admin.rents.schedule.ajaxContent')
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js"
        integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em"
        crossorigin="anonymous"></script>
<script>
    $( "body" ).on('click','.js_change_rent_date', function(){
        let url = $(this).data('url');
        $.get( url, {'date': $(this).data('date')}, function( data ) {
            $('.container-fluid').html(data.view);
        } );
    });

    $("body").on("change", "select.js_select_driver", function() {
        let el = this;
        let parent = $(el).parent().parent();
        $.post( SendUrl, {driver_id: $(el).val(), id: parent.data('tour_id')}, function( data ) {
            if (data.result == 'error') {
                toastr.error(data.message);
                $(el).val(parent.data('driver_id'));
            }
            else {
                toastr.success('данные успешно обновлены');
                parent.data('driver_id', $(el).val());
            }
        } );
    });
</script>
</body>
</html>