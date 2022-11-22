<div class="sheduleBlock">
    @if ($date->timestamp > time())
        <div style="padding-top:2em;" class="mainTitle">
            <div class="backg" style="text-align: center; font-size: 25px;">
            @php($subDay = $date->subDay())
            <span class="js_change_date" data-date="{{$subDay->format('d.m.Y')}}" style="margin-left: 5%; cursor: pointer">&laquo;&nbsp;&nbsp;</span>
            @php($addDay = $date->addDay()->format('d.m.Y'))
            @date($date)
            @php($addDay = $date->addDay()->format('d.m.Y'))
            <span class="js_change_date" data-date="{{$addDay}}"  style="cursor: pointer;"> &nbsp;&nbsp; &raquo; </span>
            @php($addDay = $date->subDay()->format('d.m.Y'))
            </div>
        </div>
        <br />
    @endif
</div>
<script>
    $(".js_change_date").click(function () {
        $('.js_date-pick').datepicker('setDate', $(this).data('date'));
        $(".js_reservation-button").click();
    })
</script>