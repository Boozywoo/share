@php($date = isset($date) ? $date : date("Y-m-d"))
<div class="row">
    <div class="col-md-4">

    </div>
    <div class="col-md-1 js_change_rent_date" style="padding-top: 1.5%" data-url="{{route('admin.rents.schedule')}}"
         data-date="{{date("Y-m-d", strtotime($date. ' -1 day'))}}">
        <div><i class="fa fa-angle-double-left" style="font-size:24px"></i></div>
    </div>
    <div class="col-md-2">
        <h2><b>{{$date}}</b></h2>
    </div>
    <div style="padding-top: 1.5%" data-url="{{route('admin.rents.schedule')}}"
         data-date="{{date("Y-m-d", strtotime($date. ' +1 day'))}}" class="col-md-1 js_change_rent_date">
        <div><i class="fa fa-angle-double-right" style="font-size:24px"></i></div>
    </div>
</div>


<div class="col-md-8 lft-panel">
    @include('admin.rents.schedule.buses')
</div>
<div class="col-md-4 rght_panel">
    <div class="row" style="    height: 590px;
    overflow: overlay;">
        <div class="col-md-12">
            <ul class="nav nav-pills nav-stacked col-md-4">
                @foreach($FreeRents as $rent)
                    <li @if(!$loop->index) class="active" @endif
                    data-tour_id="{{$rent->id}}"
                        ondragstart="dragUnset(this)"
                        id="free_rent_href_{{$loop->iteration}}">
                        <a href={{"#free_rent_".$loop->iteration}} data-toggle="tab">Аренда {{$loop->iteration}}
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content col-md-8">
                @foreach($FreeRents as $rent)
                    <div class="tab-pane {{$loop->index ? '' : 'show active'}}" id="{{'free_rent_'.$loop->iteration}}">
                        @include('admin.rents.schedule.freeRent',[
                            'rent' => $rent,
                            'id' => 'free_rent_'.$loop->iteration,
                            'href_id' => 'free_rent_href_'.$loop->iteration
                            ])
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="row" onselectstart="return false;" style="border-radius: 10px;
    background-color: seashell; margin-top: 10px">
        <div ondrop="drop2(event)" ondragover="allowDrop2(event)" class="col-md-12 text-center">
            <img align="center " src="{{asset('rent/img/busket.png')}}" width="60" alt="">
        </div>
    </div>

</div>
<script>
    let SendUrl = '{!! route('admin.rents.store') !!}';

    function removeInit() {
        var arr1 = $('.timeSlider');
        let myass = ['#myas1', '#myas2'];
        $.each(arr1, function (index, value) {
            $.each(myass, function (indexAs, valueAs) {
                $(value).find(valueAs).remove();
            });
        });
    }

    $(document).ready(function () {
        removeInit();
    });
</script>
<script type="text/javascript">
    let sliders = {!! $timeSliders !!};
    let ObjTimerSlider = [];

    function initTimeSlider(arr) {
        ObjTimerSlider = [];
        $.each(arr, function (index, value) {
            new TimeSlider({
                id: index,
                language: "en",
                defaultTime: value['time'],
                tour_id: value['id'],
                driver_id: value['driver_id'],
            })
        });
        let drivers = {!! $drivers !!};
        $(".timeSliderDiv").each(function () {
            let driver_id = $(this).data('driver_id');
            let el = this;
            let is_selected = false;
            $.each(drivers, function (index, value) {
                let is_selected = false;
                if (driver_id === value['id']) is_selected = true;
                else is_selected = false;
                $(el).find('.js_select_driver').first().append($('<option>', {
                    text: value['full_name'],
                    value: value['id'],
                    selected: is_selected,
                }));
            });
        });
        removeInit();
    }

    $('form.js_form_rent_right_panel').find('.js_save_rent').click(function () {
        let form = $(this).parent().parent();
        let url = form.data('url');
        url = url + '?' + form.serialize();
        $.post(url, {'id': form.data('id')}, function (data) {
            console.log(data);
            if (data.result == 'error') toastr.error(data.message);
            else {
                toastr.success('данные успешно обновлены');
                $('.container-fluid').html(data.view);
            }
        });
    });

    $("a").removeClass("pjax-link");
    initTimeSlider(sliders);

</script>