<form class="js_form_rent_right_panel" data-id="{{$rent->id}}" data-url="{{route('admin.rents.store')}}">
    <input hidden name="time_start" value="{{$rent->time_start}}">
    <input hidden name="date_start" value="{{$rent->date_start->format('d.m.Y')}}">
    <input hidden name="time_finish" value="{{$rent->time_finish}}">
    <input hidden name="timeSliderDriver" value="1">
    <div class="col-md-12 nv-rght m-solo">
        <p>Дата/Время: <br>
            {{$rent->pretty_time}}</p>
    </div>
    <div class="col-md-12 nv-center m-solo">
        <p>Тип рейса: <br>
            @if($rent->is_rent)
                Аренда
            @endif
        </p>
    </div>
    <div class="col-md-12 nv-bottom m-solo">
        <p style="margin-bottom: -1px">Автомобиль:</p>
        <select name="bus_id" class="form-control m-top2">
            @foreach($buses as $bus)
                <option  value="{{$bus->id}}">{{$bus->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-12 nv-last m-solo">
        <p style="margin-bottom: -1px">Водитель:</p>
        <select name="driver_id" class="form-control m-top2">
            @foreach($drivers as $driver)
                <option value="{{$driver->id}}">{{$driver->full_name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-12 nv-last m-solo">
        <button type="button" style="width: 100%; margin-bottom: 3%" class="btn btn-success m-top js_save_rent">Сохранить</button>
    </div>
</form>
